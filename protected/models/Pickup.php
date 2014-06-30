<?php

/**
 * This is the model class for table "pickups".
 *
 * The followings are the available columns in table 'pickups':
 * @property string $id
 * @property string $pickup_date
 * @property integer $booking_id
 * @property integer $driver_id
 *
 * The followings are the available model relations:
 * @property Shipment $shipment
 * @property User $driver
 */
class Pickup extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Pickup the static model class
	 */
	public $users = array();
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pickups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('booking, driver_id', 'required'),
			array('booking_id','unique'),
			array('booking_id, driver_id', 'numerical', 'integerOnly'=>true),
			array('pickup_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, pickup_date, booking_id, driver_id', 'safe', 'on'=>'search'),
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
			'booking' => array(self::BELONGS_TO, 'Booking', 'booking_id'),
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
			'pickup_date' => 'Pickup Date',
			'booking_id' => 'Booking Code',
			'driver_id' => 'Driver',
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
		$criteria->compare('pickup_date',$this->pickup_date,true);
		$criteria->compare('booking_id',$this->booking_id);
		$criteria->compare('driver_id',$this->driver_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeSave()
	{
		if($this->pickup_date != '')
		{
			$this->pickup_date = date('Y-m-d', strtotime($this->pickup_date));
		}
		return parent::beforeSave();
	}
	
	public static function listPickup($shipmentId){
		$criteria = new CDbCriteria;
		$criteria->condition = 'id = :id and shipping_status=11';
		$criteria->params[':id'] = $shipmentId;
		$model = Shipment::model()->find($criteria);
		if($model == null)
			return false;
		return $model->awb;
	}
}