<?php

/**
 * This is the model class for table "view_tracking".
 *
 * The followings are the available columns in table 'view_tracking':
 * @property string $order_id
 * @property string $shipment_type
 * @property string $status
 * @property integer $green
 * @property integer $yellow
 * @property integer $red
 * @property string $based_date
 * @property string $driver
 * @property integer $id
 * @property string $courier
 */
class ViewTracking extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ViewTracking the static model class
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
		return 'view_tracking';
	}
	public function getPrimaryKey()
	{
		return $this->id;
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('green, yellow, red, id', 'numerical', 'integerOnly'=>true),
			array('order_id', 'length', 'max'=>80),
			array('shipment_type', 'length', 'max'=>13),
			array('status', 'length', 'max'=>45),
			array('based_date', 'length', 'max'=>8),
			array('driver', 'length', 'max'=>91),
			array('courier', 'length', 'max'=>303),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('order_id, shipment_type, status, green, yellow, red, based_date, driver, id, courier', 'safe', 'on'=>'search'),
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
		);
	}
	public function getEvent()
	{
		$criteria=new CDbCriteria;
		$criteria->order='created DESC';
		return ShipmentEvent::model()->findByAttributes(array('shipment_id'=>$this->id),$criteria);
		
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'order_id' => 'Order ID',
			'shipment_type' => 'Type',
			'status' => 'Status',
			'green' => 'Green',
			'yellow' => 'Yellow',
			'red' => 'Red',
			'based_date' => 'Based Date',
			'driver' => 'Driver',
			'id' => 'ID',
			'courier' => 'Courier',
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

		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('shipment_type',$this->shipment_type,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('green',$this->green);
		$criteria->compare('yellow',$this->yellow);
		$criteria->compare('red',$this->red);
		$criteria->compare('based_date',$this->based_date,true);
		$criteria->compare('driver',$this->driver,true);
		$criteria->compare('id',$this->id);
		$criteria->compare('courier',$this->courier,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}