<?php

/**
 * This is the model class for table "rate_domestic".
 *
 * The followings are the available columns in table 'rate_domestic':
 * @property integer $id
 * @property integer $service_id
 * @property string $first_kg
 * @property string $next_kg
 * @property integer $origin_id
 * @property integer $zone_id
 * @property integer $district_id
 * @property integer $min_transit_time
 * @property integer $max_transit_time
 *
 * The followings are the available model relations:
 * @property RateCompanyService $rateCompanyService
 */
class RateDomestic extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return RateDomestic the static model class
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
		return 'rate_domestic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('service_id,origin_id,first_kg, next_kg,min_transit_time,max_transit_time', 'required'),
			array('id, origin_id, zone_id,district_id, service_id,first_kg,next_kg,zone_id,district_id,min_transit_time,max_transit_time', 'numerical', 'integerOnly' => true),
			array('first_kg, next_kg', 'length', 'max' => 12),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, first_kg, next_kg, origin_id, zone_id', 'safe', 'on' => 'search'),
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
			'rateCompanyService' => array(self::BELONGS_TO, 'RateCompanyService', 'service_id'),
			'postal' => array(self::BELONGS_TO, 'Area', 'postcode'),
			'origin' => array(self::BELONGS_TO, 'Origins', 'origin_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('model', 'ID'),
			'first_kg' => Yii::t('model', 'First Kg'),
			'next_kg' => Yii::t('model', 'Next Kg'),
			'origin_id' => Yii::t('model', 'Origin'),
			'zone_id' => Yii::t('model', 'Zone ID'),
			'service_id' => Yii::t('model', 'Service'),
			'max_transit_time' => Yii::t('model', 'Maximum Transit Time'),
			'min_transit_time' => Yii::t('model', 'Mininimum Transit Time')
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
		$criteria->compare('first_kg', $this->first_kg, true);
		$criteria->compare('next_kg', $this->next_kg, true);
		$criteria->compare('origin_id', $this->origin_id);
		$criteria->compare('zone_id', $this->zone_id);
		$criteria->compare('min_transit_time', $this->min_transit_time);
		$criteria->compare('max_transit_time', $this->max_transit_time);
		$criteria->compare('service_id', $this->service_id);

		return new CActiveDataProvider($this, array(
					'criteria' => $criteria,
					'pagination' => array(
						'pageSize' => Yii::app()->params['pagesize'],
					),
				));
	}

	public static function getServiceListAPI($from, $did, $zid, $weight = 0,$product_id,$customer_id = null,$allow_api = array())
	{
		$services = array();
		$rates = array();
		if(!(!$customer_id) && count($allow_api) == 0)
		{
			return array();
		}
		
		if(!(!$customer_id))
		{
			$join = 'JOIN rate_company_service t2 on t.service_id = t2.id JOIN rate_company t3 ON t2.rate_company_id = t3.id JOIN service_detail t4 ON t4.rate_company_service_id = t2.id JOIN product_service t5 ON t5.id = t4.product_service_id JOIN customer_discount t6 on t6.service_id = t4.id';
			$select = 't.service_id as service_id,t.first_kg as first_kg,t.next_kg as next_kg,t5.code as service_code, t3.code as carrier_code,t3.name as carrier_name,t.max_transit_time as transit_day,t5.name as service_name,t6.discount_api';
		}
		else
		{
			$join = 'JOIN rate_company_service t2 on t.service_id = t2.id JOIN rate_company t3 ON t2.rate_company_id = t3.id JOIN service_detail t4 ON t4.rate_company_service_id = t2.id JOIN product_service t5 ON t5.id = t4.product_service_id';
			$select = 't.service_id as service_id,t.first_kg as first_kg,t.next_kg as next_kg,t5.code as service_code, t3.code as carrier_code,t3.name as carrier_name,t.max_transit_time as transit_day,t5.name as service_name';
		}

		$criteria = new CDbCriteria;
		$criteria->join = $join;
		$criteria->select = $select;
		$criteria->compare('t5.product_id', $product_id,FALSE);
		$criteria->compare('origin_id', $from,FALSE);

		if ($zid == 0)
		{
			$criteria->params=array(
				':zone_id' => 0,
				':district_id'=> $did
			);
			$criteria->condition = 'zone_id=:zone_id AND district_id=:district_id';
			if(!(!$customer_id))
			{
				$criteria->params[':customer_id'] = $customer_id;
				$criteria->addCondition ('t6.customer_id =:customer_id AND t6.show_in_api = 1');
			}
			$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
			$command = $builder->createFindCommand('rate_domestic', $criteria);
			
			$rates_by_did = $command->queryAll();
			if (count($rates_by_did) > 0)
				$rates = $rates_by_did;
		}
		else
		{
			$criteria->params=array(
				':zone_id' => $zid
			);
			$criteria->condition = 'zone_id=:zone_id';
			if(!(!$customer_id))
			{
				$criteria->params[':customer_id'] = $customer_id;
				$criteria->addCondition ('t6.customer_id =:customer_id AND t6.show_in_api = 1');
			}
			$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
			$command = $builder->createFindCommand('rate_domestic', $criteria);
			$rates_by_zid = $command->queryAll();
			if (count($rates_by_zid) > 0)
				$rates = $rates_by_zid;
			else
			{
				$criteria->params=array(
					':zone_id' => 0,
					':district_id'=> $did
				);
				$criteria->condition = 'zone_id=:zone_id AND district_id=:district_id';
				if(!(!$customer_id))
				{
					$criteria->params[':customer_id'] = $customer_id;
					$criteria->addCondition ('t6.customer_id =:customer_id AND t6.show_in_api = 1');
				}
				$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
				$command = $builder->createFindCommand('rate_domestic', $criteria);
				$rates_by_did = $command->queryAll();
				if ($rates_by_did > 0)
					$rates = $rates_by_did;
			}
		}

		if (count($rates) > 0)
		{
			foreach ($rates as $rate)
			{
				if ($weight < 1)
					$ratesAvailable = 0;
				else if ($weight >= 1)
				{
					$ratesAvailable = $rate['first_kg'];
					if ($weight != 1)
						$ratesAvailable = $ratesAvailable + ($rate['next_kg'] * ceil($weight - 1));
				}
				
				if(!(!$customer_id))
					$ratesAvailable = $ratesAvailable - ($ratesAvailable * ($rate['discount_api']/100));

				$service = array(
					'service_id' => $rate['service_id'],
					'routing_code' => $rate['carrier_code'],
					'carrier_name' => $rate['carrier_name'],
					'service_code' => $rate['service_code'],
					'service_name' => $rate['service_name'],
					'price' => $ratesAvailable,
					'transits_days' => $rate['transit_day'].' hari',
				);

				array_push($services, $service);
			}
		}
		return $services;
	}
	
	public static function getServiceList($from, $did, $zid, $weight = 0,$product_id,$customer_id = null,$use_rate = array())
	{
		$services = array();
		$rates = array();
		if(!(!$customer_id) && count($use_rate) == 0)
		{
			return array();
		}
		
		if(!(!$customer_id))
		{
			$join = 'JOIN rate_company_service t2 on t.service_id = t2.id JOIN rate_company t3 ON t2.rate_company_id = t3.id JOIN service_detail t4 ON t4.rate_company_service_id = t2.id JOIN product_service t5 ON t5.id = t4.product_service_id JOIN customer_discount t6 on t6.service_id = t4.id';
			$select = 't.service_id as service_id,t.first_kg as first_kg,t.next_kg as next_kg,t5.code as service_code, t3.code as carrier_code,t3.name as carrier_name,t.max_transit_time as transit_day,t5.name as service_name,t6.discount_api,t.id as id';
		}
		else
		{
			$join = 'JOIN rate_company_service t2 on t.service_id = t2.id JOIN rate_company t3 ON t2.rate_company_id = t3.id JOIN service_detail t4 ON t4.rate_company_service_id = t2.id JOIN product_service t5 ON t5.id = t4.product_service_id';
			$select = 't.service_id as service_id,t.first_kg as first_kg,t.next_kg as next_kg,t5.code as service_code, t3.code as carrier_code,t3.name as carrier_name,t.max_transit_time as transit_day,t5.name as service_name,t.id as id';
		}

		$criteria = new CDbCriteria;
		$criteria->join = $join;
		$criteria->select = $select;
		$criteria->compare('t5.product_id', $product_id,FALSE);
		$criteria->compare('origin_id', $from,FALSE);

		if ($zid == 0)
		{
			$criteria->params=array(
				':zone_id' => 0,
				':district_id'=> $did
			);
			$criteria->condition = 'zone_id=:zone_id AND district_id=:district_id';
			if(!(!$customer_id))
			{
				$criteria->params[':customer_id'] = $customer_id;
				$criteria->addCondition ('t6.customer_id =:customer_id AND t6.use_rate = 1');
			}
			$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
			$command = $builder->createFindCommand('rate_domestic', $criteria);
			
			$rates_by_did = $command->queryAll();
			if (count($rates_by_did) > 0)
				$rates = $rates_by_did;
		}
		else
		{
			$criteria->params=array(
				':zone_id' => $zid
			);
			$criteria->condition = 'zone_id=:zone_id';
			if(!(!$customer_id))
			{
				$criteria->params[':customer_id'] = $customer_id;
				$criteria->addCondition ('t6.customer_id =:customer_id AND t6.use_rate = 1');
			}
			$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
			$command = $builder->createFindCommand('rate_domestic', $criteria);
			$rates_by_zid = $command->queryAll();
			if (count($rates_by_zid) > 0)
				$rates = $rates_by_zid;
			else
			{
				$criteria->params=array(
					':zone_id' => 0,
					':district_id'=> $did
				);
				$criteria->condition = 'zone_id=:zone_id AND district_id=:district_id';
				if(!(!$customer_id))
				{
					$criteria->params[':customer_id'] = $customer_id;
					$criteria->addCondition ('t6.customer_id =:customer_id AND t6.use_rate = 1');
				}
				$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
				$command = $builder->createFindCommand('rate_domestic', $criteria);
				$rates_by_did = $command->queryAll();
				if ($rates_by_did > 0)
					$rates = $rates_by_did;
			}
		}

		if (count($rates) > 0)
		{
			foreach ($rates as $rate)
			{
				if ($weight < 1)
					$ratesAvailable = 0;
				else if ($weight >= 1)
				{
					$ratesAvailable = $rate['first_kg'];
					if ($weight != 1)
						$ratesAvailable = $ratesAvailable + ($rate['next_kg'] * ceil($weight - 1));
				}
				
				if(!(!$customer_id))
					$ratesAvailable = $ratesAvailable - ($ratesAvailable * ($rate['discount_api']/100));

				$service = array(
					'id' => $rate['id'],
					'service_id' => $rate['service_id'],
					'routing_code' => $rate['carrier_code'],
					'carrier_name' => $rate['carrier_name'],
					'service_code' => $rate['service_code'],
					'service_name' => $rate['service_name'],
					'price' => $ratesAvailable,
					'transits_days' => $rate['transit_day'].' hari',
				);

				array_push($services, $service);
			}
		}
		return $services;
	}

	public static function getRatePrice($service_id, $from, $district_id, $zone_id, $weight,$discount)
	{
		$ratePrice = '';
		if ($zone_id == 0)
		{
			$rate_by_district = RateDomestic::model()->findByAttributes(array('service_id' => $service_id, 'origin_id' => $from, 'district_id' => $district_id, 'zone_id' => $zone_id));
			if (($rate_by_district instanceof RateDomestic))
				$ratePrice = $rate_by_district;
		}
		else
		{
			$rate_by_zone = RateDomestic::model()->findByAttributes(array('service_id' => $service_id, 'origin_id' => $from, 'zone_id' => $zone_id));
			if (($rate_by_zone instanceof RateDomestic))
				$ratePrice = $rate_by_zone;
			else
			{
				$rate_by_district = RateDomestic::model()->findByAttributes(array('service_id' => $service_id, 'origin_id' => $from, 'district_id' => $district_id, 'zone_id' => 0));

				if (($rate_by_district instanceof RateDomestic))
					$ratePrice = $rate_by_district;
			}
		}


		if (!($ratePrice instanceof RateDomestic))
			return 0;

		if ($weight < 1)
			$rate = 0;
		else if ($weight >= 1)
		{
			$rate = $ratePrice->first_kg;
			if ($weight != 1)
				$rate = $rate + ($ratePrice->next_kg * ceil($weight - 1));
		}
		return $rate - ($rate * $discount/100);
	}

	public static function getAllServiceRate($from, $to, $weight)
	{

		$from = Origins::getOriginId($from);

		$model = RateDomestic::model()
				->findAll('origin_id=:zf and zone_id=:zt', array(
			':zf' => $from,
			':zt' => $to,
				));
		if ($model != null)
		{
			foreach ($model as $key => $item)
			{
				$rate = $item->first_kg;
				if ($weight != 1)
				{
					$rate = $rate + ($item->next_kg * ceil($weight - 1));
				}

				$result[$key] = array(
					'service_id' => $item->service_id,
					'company_name' => $item->rateCompanyService->company->name,
					'service_name' => $item->rateCompanyService->name,
					'price' => $rate,
					'transit_days' => $item->max_transit_time,
				);
			}
			return $result;
		}
		else
		{
			return false;
		}
	}

	public function getRateByService($from, $to, $service, $weight = 1)
	{
		$from = Origins::getOriginId($from);
		$to = Area::getPostCode($to);
		$service = self::getServiceId($service);
		$model = RateDomestic::model();
		$criteria = new CDbCriteria;
		$criteria->condition = "origin_id=" . $from;
		$criteria->addCondition('postal_to=' . $to);
		$criteria->addCondition('service_id=' . $service);
		$RatePrice = $model->find($criteria);
		if ($RatePrice != null)
		{
			$rate = $RatePrice->first_kg;
			if ($weight != 1)
			{
				$rate = $rate + ($RatePrice->next_kg * ceil($weight - 1));
			}
			return number_format($rate, 2, '.', '');
		}
		return false;
	}

	public function beforeValidate()
	{
		if ($this->isNewRecord)
		{
			$this->updated = $this->created = time();
		}
		if (!$this->isNewRecord)
		{
			$this->updated = time();
		}
		return parent::beforeValidate();
	}

	public static function checkZoneId($serviceId, $originId, $zoneId)
	{
		$criteria = new CDbCriteria;
		$criteria->condition = "service_id=:service_id AND origin_id=:origin_id AND zone_id=:zone_id";
		$criteria->params = array(':service_id' => $serviceId, ':origin_id' => $originId, ':zone_id' => $zoneId);
		$check = RateDomestic::model()->count($criteria);
		if ($check == 0)
			return false;
		else
			return true;
	}

	public static function getRatePriceBaseOnId($id, $total_weight)
	{
		$ratePrice = self::model()->findByPk($id);
		$rate = 0;
		if (($ratePrice instanceof RateDomestic))
		{
			if ($total_weight < 1)
				$rate = 0;
			else if ($total_weight >= 1)
			{
				$rate = $ratePrice->first_kg;
				if ($total_weight != 1)
					$rate = $rate + ($ratePrice->next_kg * ceil($total_weight - 1));
			}
		}
		return $rate;
	}
}