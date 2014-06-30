<?php

/**
 * This is the model class for table "shipment_status".
 *
 * The followings are the available columns in table 'shipment_status':
 * @property integer $id
 * @property string $status
 * @property integer $green
 * @property integer $yellow
 * @property integer $red
 * @property string $based_date
 *
 * The followings are the available model relations:
 * @property Shipments[] $shipments
 */
class ShipmentStatus extends CActiveRecord
{
	const OTW = 5;
	const POD = 10;
	const MDE = 13;
	const ARR = 3;

	/**
	 * Returns the static model of the specified AR class.
	 * @return ShipmentStatus the static model class
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
		return 'shipment_status';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('green, yellow, red, based_date', 'required'),
			array('green, yellow, red', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>45),
			array('based_date', 'length', 'max'=>8),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, status, green, yellow, red, based_date', 'safe', 'on'=>'search'),
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
			'shipments' => array(self::HAS_MANY, 'Shipments', 'shipping_status'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'status' => 'Status',
			'green' => 'Green',
			'yellow' => 'Yellow',
			'red' => 'Red',
			'based_date' => 'Based Date',
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
		$criteria->compare('status',$this->status,true);
		$criteria->compare('green',$this->green);
		$criteria->compare('yellow',$this->yellow);
		$criteria->compare('red',$this->red);
		$criteria->compare('based_date',$this->based_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}