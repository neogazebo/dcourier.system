<?php

/**
 * This is the model class for table "shipment_additional_charges".
 *
 * The followings are the available columns in table 'shipment_additional_charges':
 * @property string $id
 * @property string $name
 * @property string $desc
 * @property string $cost
 * @property integer $shipment_id
 *
 * The followings are the available model relations:
 * @property Shipment $shipment
 */
class ShipmentAdditionalCharge extends CActiveRecord
{
	const SurchargeAddWeightRate = 5000;
	const SurchargeAddKmRate = 5000;
	const SurchargeAddStop = 5000;
	const SurchargePOD = 1000;
	
	public static $shipment;

	/**
	 * Returns the static model of the specified AR class.
	 * @return ShipmentAdditionalCharge the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'shipment_additional_charges';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cost', 'required'),
			array('shipment_id', 'required','on' => 'insert,update'),
			array('shipment_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('cost', 'length', 'max'=>12),
			array('cost', 'numerical', 'integerOnly'=>FALSE),
			array('desc', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, desc, cost, shipment_id', 'safe', 'on'=>'search'),
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
			'shipment' => array(self::BELONGS_TO, 'Shipment', 'shipment_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'desc' => 'Desc',
			'cost' => 'Cost',
			'shipment_id' => 'Shipment',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('cost',$this->cost,true);
		$criteria->compare('shipment_id',$this->shipment_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	static public function getSurcharges() 
	{
		return array(
			array('code'=>'LSX','surcharge'=>'Add Weight','value'=>'getSurchargeAddWeight'),
			array('code'=>'LSX','surcharge'=>'Add Km','value'=>'getSurchargeAddKm'),
			array('code'=>'LSX','surcharge'=>'Add Stop','value'=>'getSurchargeAddStop'),
			array('code'=>'LSX','surcharge'=>'Fragile','value'=>'getSurchargeFragile'),
			array('code'=>'LSX','surcharge'=>'Shopping Fee','value'=>'getSurchargeShoppingFee'),
			array('code'=>'LSX','surcharge'=>'Cash On Delivery','value'=>'getSurchargeCOD'),
			array('code'=>'LSX','surcharge'=>'Insurance','value'=>'getSurchargeInsurance'),
			array('code'=>'LSX','surcharge'=>'POD Retreival','value'=>'getSurchargePOD'),
			
			array('code'=>'HRX','surcharge'=>'Add Weight','value'=>'getSurchargeAddWeight'),
			array('code'=>'HRX','surcharge'=>'Add Km','value'=>'getSurchargeAddKm'),
			array('code'=>'HRX','surcharge'=>'Add Stop','value'=>'getSurchargeAddStop'),
			array('code'=>'HRX','surcharge'=>'Fragile','value'=>'getSurchargeFragile'),
			array('code'=>'HRX','surcharge'=>'Shopping Fee','value'=>'getSurchargeShoppingFee'),
			array('code'=>'HRX','surcharge'=>'Cash On Delivery','value'=>'getSurchargeCOD'),
			array('code'=>'HRX','surcharge'=>'Insurance','value'=>'getSurchargeInsurance'),
			array('code'=>'HRX','surcharge'=>'POD Retreival','value'=>'getSurchargePOD'),
			
			array('code'=>'BSX','surcharge'=>'Fragile','value'=>'getSurchargeFragile'),
			array('code'=>'BSX','surcharge'=>'Cash On Delivery','value'=>'getSurchargeCOD'),
			array('code'=>'BSX','surcharge'=>'Insurance','value'=>'getSurchargeInsurance'),
			array('code'=>'BSX','surcharge'=>'POD Retreival','value'=>'getSurchargePOD'),
			
			array('code'=>'LUX','surcharge'=>'Fragile','value'=>'getSurchargeFragile'),
			array('code'=>'LUX','surcharge'=>'Cash On Delivery','value'=>'getSurchargeCOD'),
			array('code'=>'LUX','surcharge'=>'Insurance','value'=>'getSurchargeInsurance'),
			array('code'=>'LUX','surcharge'=>'POD Retreival','value'=>'getSurchargePOD'),
			
			array('code'=>'HMX','surcharge'=>'Fragile','value'=>'getSurchargeFragile'),
			array('code'=>'HMX','surcharge'=>'Cash On Delivery','value'=>'getSurchargeCOD'),
			array('code'=>'HMX','surcharge'=>'Insurance','value'=>'getSurchargeInsurance'),
			array('code'=>'HMX','surcharge'=>'POD Retreival','value'=>'getSurchargePOD'),
			
			array('code'=>'HRQ','surcharge'=>'Insurance','value'=>'getSurchargeInsurance'),
			array('code'=>'HRQ','surcharge'=>'Add Weight','value'=>'getSurchargeRemoteArea'),
			array('code'=>'HRQ','surcharge'=>'Add Weight','value'=>'getSurchargeOversize'),
			
			array('code'=>'BSQ','surcharge'=>'Insurance','value'=>'getSurchargeInsurance'),
			array('code'=>'BSQ','surcharge'=>'Remote Area','value'=>'getSurchargeRemoteArea'),
			array('code'=>'BSQ','surcharge'=>'Oversize','value'=>'getSurchargeOversize'),
			
			array('code'=>'LUQ','surcharge'=>'Insurance','value'=>'getSurchargeInsurance'),
			array('code'=>'LUQ','surcharge'=>'Remote Area','value'=>'getSurchargeRemoteArea'),
			array('code'=>'LUQ','surcharge'=>'Oversize','value'=>'getSurchargeOversize'),
			
			array('code'=>'HMQ','surcharge'=>'Insurance','value'=>'getSurchargeInsurance'),
			array('code'=>'HMQ','surcharge'=>'Remote Area','value'=>'getSurchargeRemoteArea'),
			array('code'=>'HMQ','surcharge'=>'Oversize','value'=>'getSurchargeOversize'),
			
			array('code'=>'IEX','surcharge'=>'Insurance','value'=>'getSurchargeInsurance'),
			array('code'=>'IEX','surcharge'=>'Remote Area','value'=>'getSurchargeRemoteArea'),
			array('code'=>'IEX','surcharge'=>'Oversize','value'=>'getSurchargeOversize'),
			
			array('code'=>'IMX','surcharge'=>'Insurance','value'=>'getSurchargeInsurance'),
			array('code'=>'IMX','surcharge'=>'Remote Area','value'=>'getSurchargeRemoteArea'),
			array('code'=>'IMX','surcharge'=>'Oversize','value'=>'getSurchargeOversize'),
		);
	}
	
	static public function initSurcharges(Shipment $shipment_model)
	{
		self::$shipment = $shipment_model;
		$total_cost = self::setSurecharges();
		return $total_cost;
	}
	
	public static function setSurecharges()
	{
		$surcharges = self::getSurcharges();
		
		$code = self::$shipment->service_code;
		$total_cost = 0;
		foreach($surcharges as $surcharge) 
		{
			if($surcharge['code'] == $code && isset($surcharge['value']))
			{
//				$charge = self::model()->findByAttributes(array('shipment_id'=>$shipment->id,'name'=>$surcharge['code']));
				$charge = new ShipmentAdditionalCharge;
				$charge->name = $surcharge['surcharge'];
				$charge->shipment_id = self::$shipment->id;
				$func = $surcharge['value'];
				$charge->cost = $charge->$func();
				$total_cost = $total_cost + $charge->cost;
				$charge->save();
			}
		}
		return $total_cost;
	}
	
	public function getSurchargeAddWeight()
	{
		return self::$shipment->package_weight * self::SurchargeAddWeightRate;
	}

	public function getSurchargeAddKm()
	{
		return self::SurchargeAddKmRate;
	}
	
	public function getSurchargeAddStop()
	{
		return self::SurchargeAddStop;
	}
	
	public function getSurchargeFragile()
	{
		if(self::$shipment->fragile)
			return self::$shipment->shipping_charges * 0.25;
	}
	
	public function getSurchargeShoppingFee()
	{
		if(self::$shipment->package_value > 100000)
			return self::$shipment->package_value * 0.05;
	}
	
	public function getSurchargeCOD()
	{
		if(self::$shipment->is_cod)
			return self::$shipment->shipping_charges * 0.5;
	}
	
	public function getSurchargeInsurance()
	{
		if(self::$shipment->insurance)
			return self::$shipment->package_value * 0.03;
	}
	
	public function getSurchargePOD()
	{
		return self::SurchargePOD;
	}
	
	public function getSurchargeRemoteArea()
	{
		return 0;
	}
	
	public function getSurchargeOversize()
	{
		return 0;
	}
}