<?php

/**
 * This is the model class for table "driver_location".
 *
 * The followings are the available columns in table 'driver_location':
 * @property integer $id
 * @property integer $driver_user_id
 * @property integer $time
 * @property double $lat
 * @property double $long
 *
 * The followings are the available model relations:
 * @property Driver $driverUser
 */
class DriverLocation extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DriverLocation the static model class
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
		return 'driver_location';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('driver_user_id', 'required'),
			array('driver_user_id, time', 'numerical', 'integerOnly'=>true),
			array('lat, long', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, driver_user_id, time, lat, long', 'safe', 'on'=>'search'),
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
			'driverUser' => array(self::BELONGS_TO, 'Driver', 'driver_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'driver_user_id' => 'Driver User',
			'time' => 'Time',
			'lat' => 'Lat',
			'long' => 'Long',
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
		$criteria->compare('driver_user_id',$this->driver_user_id);
		$criteria->compare('time',$this->time);
		$criteria->compare('lat',$this->lat);
		$criteria->compare('long',$this->long);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}