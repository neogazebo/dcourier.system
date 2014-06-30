<?php

/**
 * This is the model class for table "rate_city".
 *
 * The followings are the available columns in table 'rate_city':
 * @property integer $id
 * @property integer $service_id
 * @property integer $weight_inc
 * @property string $origin
 * @property string $price
 * @property integer $transit_time
 *
 * The followings are the available model relations:
 * @property RateCompanyService $service
 */
class RateCity extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RateCity the static model class
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
		return 'rate_city';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('service_id', 'required'),
			array('service_id, weight_inc, transit_time', 'numerical', 'integerOnly' => true),
			array('origin', 'length', 'max' => 45),
			array('price', 'length', 'max' => 12),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, service_id, weight_inc, origin, price', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'service' => array(self::BELONGS_TO, 'RateCompanyService', 'service_id'),
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
			'weight_inc' => 'Weight Inc',
			'origin' => 'Origin',
			'price' => 'Price',
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
		$criteria->compare('transit_time', $this->service_id);
		$criteria->compare('weight_inc', $this->weight_inc);
		$criteria->compare('origin', $this->origin, true);
		$criteria->compare('price', $this->price, true);

		return new CActiveDataProvider($this, array(
					'criteria' => $criteria,
				));
	}
	
	public static function increment($weight,$inc)
	{
		$bagi = $weight/$inc;
		return ceil($bagi);
	}

	public static function getCityRateAPI($product_id, $routing_code, $weight,$customer_id = null,$allow_api=array())
	{
		$criteria = new CDbCriteria;
		if(!(!$customer_id) && count($allow_api) == 0)
		{
			return array();
		}
		if(!(!$customer_id))
		{
			$join = 'JOIN rate_company_service t2 on t.service_id = t2.id JOIN rate_company t3 ON t2.rate_company_id = t3.id JOIN service_detail t4 ON t4.rate_company_service_id = t2.id JOIN product_service t5 ON t5.id = t4.product_service_id JOIN customer_discount t6 on t6.service_id = t4.id';
			$select = 't.service_id as service_id,t.weight_inc ,t.price , t5.code as service_code, t3.code as carrier_code,t3.name as carrier_name,t5.name as service_name,t4.id,t6.harga_api,t6.discount_api,t.transit_time';
		}
		else
		{
			$join = 'JOIN rate_company_service t2 on t.service_id = t2.id JOIN rate_company t3 ON t2.rate_company_id = t3.id JOIN service_detail t4 ON t4.rate_company_service_id = t2.id JOIN product_service t5 ON t5.id = t4.product_service_id';
			$select = 't.service_id as service_id,t.weight_inc ,t.price , t5.code as service_code, t3.code as carrier_code,t3.name as carrier_name,t5.name as service_name,t4.id,t.transit_time';
		}
		
		$criteria->join = $join;
		$criteria->select = $select;
		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$criteria->params=array(
			':product_id' => $product_id,
		);
		$criteria->condition = 't5.product_id=:product_id';
		if(!(!$customer_id))
		{
			$criteria->params[':customer_id'] = $customer_id;
			$criteria->addCondition ('t6.customer_id =:customer_id AND t6.show_in_api = 1');
		}
		$command = $builder->createFindCommand('rate_city', $criteria);
		$rates = $command->queryAll();
		$services = array();
		foreach ($rates as $rate)
		{
			if(!(!$customer_id))
			{
				if($rate['harga_api'] != 0)
					$price = $rate['harga_api'] * self::increment($weight, $rate['weight_inc']);
				else
					$price = ($rate['price'] - ($rate['price'] * ($rate['discount_api']/100))) * self::increment($weight, $rate['weight_inc']);
			}
			else
				$price = $rate['price'] * self::increment($weight, $rate['weight_inc']);
			$service = array(
				'service_id' => $rate['service_id'],
				'routing_code' => $routing_code,
				'carrier_name' => $rate['carrier_name'],
				'service_code' => $rate['service_code'],
				'service_name' => $rate['service_name'],
				'price' => $price,
				'transits_days' => $rate['transit_time'].' hari',
			);
			array_push($services, $service);
		}
		return $services;
	}
	
	public static function getCityRate($product_id, $routing_code, $weight,$customer_id = null,$use_rate=array())
	{
		$criteria = new CDbCriteria;
		if(!(!$customer_id) && count($use_rate) == 0)
		{
			return array();
		}
		if(!(!$customer_id))
		{
			$join = 'JOIN rate_company_service t2 on t.service_id = t2.id JOIN rate_company t3 ON t2.rate_company_id = t3.id JOIN service_detail t4 ON t4.rate_company_service_id = t2.id JOIN product_service t5 ON t5.id = t4.product_service_id JOIN customer_discount t6 on t6.service_id = t4.id';
			$select = 't.service_id as service_id,t.weight_inc ,t.price , t5.code as service_code, t3.code as carrier_code,t3.name as carrier_name,t5.name as service_name,t.id as id,t6.harga_api,t6.discount_api,t.transit_time';
		}
		else
		{
			$join = 'JOIN rate_company_service t2 on t.service_id = t2.id JOIN rate_company t3 ON t2.rate_company_id = t3.id JOIN service_detail t4 ON t4.rate_company_service_id = t2.id JOIN product_service t5 ON t5.id = t4.product_service_id';
			$select = 't.service_id as service_id,t.weight_inc ,t.price , t5.code as service_code, t3.code as carrier_code,t3.name as carrier_name,t5.name as service_name,t.id as id,t.transit_time';
		}
		
		$criteria->join = $join;
		$criteria->select = $select;
		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$criteria->params=array(
			':product_id' => $product_id,
		);
		$criteria->condition = 't5.product_id=:product_id';
		if(!(!$customer_id))
		{
			$criteria->params[':customer_id'] = $customer_id;
			$criteria->addCondition ('t6.customer_id =:customer_id AND t6.use_rate = 1');
		}
		$command = $builder->createFindCommand('rate_city', $criteria);
		$rates = $command->queryAll();
		$services = array();
		foreach ($rates as $rate)
		{
			if(!(!$customer_id))
			{
				if($rate['harga_api'] != 0)
					$price = $rate['harga_api'] * self::increment($weight, $rate['weight_inc']);
				else
					$price = ($rate['price'] - ($rate['price'] * ($rate['discount_api']/100))) * self::increment($weight, $rate['weight_inc']);
			}
			else
				$price = $rate['price'] * self::increment($weight, $rate['weight_inc']);
			$service = array(
				'id' => $rate['id'],
				'service_id' => $rate['service_id'],
				'routing_code' => $routing_code,
				'carrier_name' => $rate['carrier_name'],
				'service_code' => $rate['service_code'],
				'service_name' => $rate['service_name'],
				'price' => $price,
				'transits_days' => $rate['transit_time'].' hari',
			);
			array_push($services, $service);
		}
		return $services;
	}
}