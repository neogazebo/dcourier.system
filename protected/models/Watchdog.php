<?php

/**
 * This is the model class for table "watchdog".
 *
 * The followings are the available columns in table 'watchdog':
 * @property string $id
 * @property integer $user_id
 * @property string $type
 * @property string $message
 * @property string $severity
 * @property integer $time
 * @property string $ip_address
 * @property string $vars
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class Watchdog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Watchdog the static model class
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
		return 'watchdog';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('user_id, time', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>16),
			array('severity', 'length', 'max'=>45),
			array('ip_address', 'length', 'max'=>15),
			array('message, vars', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, type, message, severity, time, ip_address, vars', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'type' => 'Type',
			'message' => 'Message',
			'severity' => 'Severity',
			'time' => 'Time',
			'ip_address' => 'Ip Address',
			'vars' => 'Vars',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('severity',$this->severity,true);
		$criteria->compare('time',$this->time);
		$criteria->compare('ip_address',$this->ip_address,true);
		$criteria->compare('vars',$this->vars,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}