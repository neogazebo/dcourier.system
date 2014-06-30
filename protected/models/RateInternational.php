<?php

/**
 * This is the model class for table "rate_international".
 *
 * The followings are the available columns in table 'rate_international':
 * @property integer $id
 * @property integer $service_id
 * @property string $type
 * @property double $weight
 * @property string $zone_a
 * @property string $zone_b
 * @property string $zone_c
 * @property string $zone_d
 * @property string $zone_e
 * @property string $zone_f
 * @property string $zone_g
 *
 * The followings are the available model relations:
 * @property InternationalCompanyService $service
 */
class RateInternational extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return RateInternational the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rate_international';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('service_id,type', 'required'),
			array('service_id,type,weight', 'uniqueCombination'),
			array('service_id', 'numerical', 'integerOnly' => true),
			array('weight', 'numerical'),
			array('type', 'length', 'max' => 8),
			array('zone_a, zone_b, zone_c, zone_d, zone_e, zone_f, zone_g', 'length', 'max' => 12),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, service_id, type, weight, zone_a, zone_b, zone_c, zone_d, zone_e, zone_f, zone_g', 'safe', 'on' => 'search'),
		);
	}

	public function uniqueCombination($attribute, $params)
	{
		if (!$this->hasErrors())
		{

			$criteria = new CDbCriteria;

			if (!$this->isNewRecord)
				$criteria->addNotInCondition('id', array($this->id), 'AND');

			$not_unique = self::model()->findByAttributes(array('service_id' => $this->service_id, 'weight' => $this->weight, 'type' => $this->type), $criteria);

			if (($not_unique instanceof RateInternational))
			{
				$message = 'Rate price ini sudah ada, silahkan masukan service, berat atau type paket yang lain';
				$this->addErrors(array('service_id' => $message, 'type' => $message, 'weight' => $message));
			}
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'company_service' => array(self::BELONGS_TO, 'RateCompanyService', 'service_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'service_id' => 'Service',
			'type' => 'Package Type',
			'weight' => 'Weight',
			'zone_a' => 'Zone A',
			'zone_b' => 'Zone B',
			'zone_c' => 'Zone C',
			'zone_d' => 'Zone D',
			'zone_e' => 'Zone E',
			'zone_f' => 'Zone F',
			'zone_g' => 'Zone G',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('service_id', $this->service_id);
		$criteria->compare('type', $this->type, true);
		$criteria->compare('weight', $this->weight);
		$criteria->compare('zone_a', $this->zone_a, true);
		$criteria->compare('zone_b', $this->zone_b, true);
		$criteria->compare('zone_c', $this->zone_c, true);
		$criteria->compare('zone_d', $this->zone_d, true);
		$criteria->compare('zone_e', $this->zone_e, true);
		$criteria->compare('zone_f', $this->zone_f, true);
		$criteria->compare('zone_g', $this->zone_g, true);

		return new CActiveDataProvider($this, array(
					'criteria' => $criteria,
				));
	}

	public function type()
	{
		return array(
			'document' => 'Document',
			'parcel' => 'Packages'
		);
	}

	public static function modulo05($x)
	{
		$floor = floor($x);
		$sisa = $x - $floor;

		if ($sisa > 0.5)
			$hasil = $floor + 1;
		elseif ($sisa > 0)
			$hasil = $floor + 0.5;
		else
			$hasil = $floor;

		return $hasil;
	}

	public static function getRatePrice($service_id, $total_weight, $type, $raw_zone,$discount)
	{
		$weight_to_search = self::modulo05($total_weight);

		if (is_array($raw_zone))
		{
			foreach ($raw_zone as $key => $val)
				$zone = $val;
		}
		else
			$zone = $raw_zone;
		$criteria = new CDbCriteria;
		$criteria->select = $zone . ' as price';
		$criteria->addColumnCondition(array('weight' => $weight_to_search, 'type' => $type, 'service_id' => $service_id));

		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$command = $builder->createFindCommand('rate_international', $criteria);
		$rate = $command->queryRow();

		if (!(!$rate))
			return $rate['price'] - ($rate['price'] * $discount/100);

		return 0;
	}

	public static function getServicesAPI($total_weight, $zone, $transit_time,$customer_id=null,$allow_api=array())
	{
		$weight_to_search = self::modulo05($total_weight);
		$service = array();
		$price = 0;
		if(!(!$customer_id) && count($allow_api) == 0)
		{
			return array();
		}

		if(!(!$customer_id))
		{
			$join = 'JOIN rate_company_service t2 on t.service_id = t2.id JOIN rate_company t3 ON t2.rate_company_id = t3.id JOIN service_detail t4 ON t4.rate_company_service_id = t2.id JOIN product_service t5 ON t5.id = t4.product_service_id JOIN customer_discount t6 on t6.service_id = t4.id';
			$select = ',t5.code as service_code, t3.code as carrier_code, t.type, t.service_id, t3.name as carrier_name,t5.name as service_name,t6.discount_api';
		}
		else
		{
			$join = 'JOIN rate_company_service t2 on t.service_id = t2.id JOIN rate_company t3 ON t2.rate_company_id = t3.id JOIN service_detail t4 ON t4.rate_company_service_id = t2.id JOIN product_service t5 ON t5.id = t4.product_service_id';
			$select = ',t5.code as service_code, t3.code as carrier_code, t.type, t.service_id, t3.name as carrier_name,t5.name as service_name';
		}
		
		$criteria = new CDbCriteria;
		$criteria->join = $join;
		$criteria->select = $zone . $select;
		$criteria->addColumnCondition(array('t.weight' => $weight_to_search));
		if(!(!$customer_id))
		{
			$criteria->params[':customer_id'] = $customer_id;
			$criteria->addCondition ('t6.customer_id =:customer_id AND t6.show_in_api = 1');
		}

		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$command = $builder->createFindCommand('rate_international', $criteria);
		$rates = $command->queryAll();

		if (!empty($rates))
		{
			foreach ($rates as $rate)
			{
				if(!(!$customer_id))
					$price = $rate[$zone] - ($rate[$zone] * ($rate['discount_api']/100));
				else
					$price = $rate[$zone];
				array_push($service, array(
					'price' => $price,
					'service_id' => $rate['service_id'],
					'transits_days' => $transit_time. ' days',
					'carrier_name' => $rate['carrier_name'],
					'service_name' => $rate['service_name'],
					'service_code' => $rate['service_code'],
					'routing_code' => $rate['carrier_code'],
					'package_type' => $rate['type']
						)
				);
			}
		}

		return $service;
	}
	
	public static function getServices($total_weight, $zone, $transit_time,$customer_id=null,$use_rate=array())
	{
		$weight_to_search = self::modulo05($total_weight);
		$service = array();
		$price = 0;
		if(!(!$customer_id) && count($use_rate) == 0)
		{
			return array();
		}

		if(!(!$customer_id))
		{
			$join = 'JOIN rate_company_service t2 on t.service_id = t2.id JOIN rate_company t3 ON t2.rate_company_id = t3.id JOIN service_detail t4 ON t4.rate_company_service_id = t2.id JOIN product_service t5 ON t5.id = t4.product_service_id JOIN customer_discount t6 on t6.service_id = t4.id';
			$select = ',t5.code as service_code, t3.code as carrier_code, t.type, t.service_id, t3.name as carrier_name,t5.name as service_name,t6.discount_api,t.id as id';
		}
		else
		{
			$join = 'JOIN rate_company_service t2 on t.service_id = t2.id JOIN rate_company t3 ON t2.rate_company_id = t3.id JOIN service_detail t4 ON t4.rate_company_service_id = t2.id JOIN product_service t5 ON t5.id = t4.product_service_id';
			$select = ',t5.code as service_code, t3.code as carrier_code, t.type, t.service_id, t3.name as carrier_name,t5.name as service_name,t.id as id';
		}
		
		$criteria = new CDbCriteria;
		$criteria->join = $join;
		$criteria->select = $zone . $select;
		$criteria->addColumnCondition(array('t.weight' => $weight_to_search));
		if(!(!$customer_id))
			$criteria->addCondition ('t6.use_rate = 1');

		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$command = $builder->createFindCommand('rate_international', $criteria);
		$rates = $command->queryAll();

		if (!empty($rates))
		{
			foreach ($rates as $rate)
			{
				if(!(!$customer_id))
					$price = $rate[$zone] - ($rate[$zone] * ($rate['discount_api']/100));
				else
					$price = $rate[$zone];
				array_push($service, array(
					'price' => $price,
					'id' => $rate['id'],
					'service_id' => $rate['service_id'],
					'transits_days' => $transit_time. ' days',
					'carrier_name' => $rate['carrier_name'],
					'service_name' => $rate['service_name'],
					'service_code' => $rate['service_code'],
					'routing_code' => $rate['carrier_code'],
					'package_type' => $rate['type']
						)
				);
			}
		}

		return $service;
	}
}