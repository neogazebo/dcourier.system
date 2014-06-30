<?php

/**
 * This is the model class for table "booking".
 *
 * The followings are the available columns in table 'booking':
 * @property integer $id
 * @property string $created
 * @property string $booking_code
 * @property integer $customer_id
 * @property string $name
 * @property string $request_by
 * @property string $address
 * @property string $city
 * @property string $postal
 * @property string $phone
 * @property string $country
 * @property string $pickup_date
 * @property string $shipment_ready_time
 * @property string $office_close_time
 * @property string $address_type
 * @property string $pickup_note
 * @property string $shipment_location
 * @property integer $shipment_id
 */
class Booking extends CActiveRecord
{
	public $payer; // sementara, nanti dibikin fieldnya di db
	public $customer_account;
	public $shipment_id;
	public $list_address_type = array('Office','Residential','Public');
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Booking the static model class
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
		return 'booking';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id', 'numerical', 'integerOnly'=>true),
			array('created, booking_code, country, address_type, shipment_location, phone', 'length', 'max'=>45),
			array('city', 'length', 'max'=>80),
			array('name,request_by', 'length', 'max'=>100),
			array('postal', 'length', 'max'=>5),
			array('address, pickup_date, shipment_ready_time, office_close_time, pickup_note, shipment_id', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, created, booking_code, customer_id, address, city, postal, country, pickup_date, shipment_ready_time, office_close_time, address_type, pickup_note, shipment_location', 'safe', 'on'=>'search'),
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
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
			'driver' => array(self::BELONGS_TO, 'Driver', 'driver_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'created' => 'Created',
			'booking_code' => 'Booking Code',
			'customer_id' => 'Customer',
			'name' => 'Name',
			'address' => 'Address',
			'city' => 'City',
			'postal' => 'Postal',
			'country' => 'Country',
			'pickup_date' => 'Pickup Date',
			'shipment_ready_time' => 'Shipment Ready Time',
			'office_close_time' => 'Office Close Time',
			'address_type' => 'Address Type',
			'pickup_note' => 'Remarks',
			'shipment_location' => 'Shipment Location',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('booking_code',$this->booking_code,true);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('postal',$this->postal,true);
		$criteria->compare('postal',$this->phone,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('pickup_date',$this->pickup_date,true);
		$criteria->compare('shipment_ready_time',$this->shipment_ready_time,true);
		$criteria->compare('office_close_time',$this->office_close_time,true);
		$criteria->compare('address_type',$this->address_type,true);
		$criteria->compare('pickup_note',$this->pickup_note,true);
		$criteria->compare('shipment_location',$this->shipment_location,true);
		$criteria->order = 'id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function beforeSave()
	{
		$this->pickup_date = date('Y-m-d', strtotime($this->pickup_date));
		if($this->isNewRecord)
		{
			$this->created = time();
		}
		return parent::beforeSave();
	}
}