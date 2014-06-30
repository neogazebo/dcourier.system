<?php

/**
 * This is the model class for table "driver".
 *
 * The followings are the available columns in table 'driver':
 * @property integer $user_id
 * @property string $routing_code
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property DriverLocation[] $driverLocations
 */
class Driver extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Driver the static model class
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
		return 'driver';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'unique'),
			array('user_id', 'required'),
			array('token,message','safe'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('routing_code', 'length', 'max'=>3),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, routing_code', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'driverLocations' => array(self::HAS_MANY, 'DriverLocation', 'driver_user_id'),
			'booking' => array(self::HAS_ONE,'Booking','driver_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'routing_code' => 'Routing Code',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('routing_code',$this->routing_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public static function generateToken($id){
		$driver = Driver::model()->findByPk($id);
		$driver->token = sha1(time());
		$driver->save(false);
		return $driver->token;
	}
	public static function getUserId($token){
		$driver = Driver::model()->findByAttributes(array('token'=>$token));
		if($driver==null){
			echo CJSON::encode(array('error'=>'user not register'));
			Yii::app()->end();
		}
		else
			return $driver->user_id;
	}
}