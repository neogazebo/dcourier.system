<?php

/**
 * This is the model class for table "user_logs".
 *
 * The followings are the available columns in table 'user_logs':
 * @property integer $id
 * @property integer $user_id
 * @property integer $time
 * @property string $type
 * @property string $message
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UserLogs extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return UserLogs the static model class
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
		return 'user_logs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, type', 'required'),
			array('user_id, time', 'numerical', 'integerOnly' => true),
			array('type', 'length', 'max' => 7),
			array('message', 'length', 'max' => 255),
			array('class','length', 'max' => 80),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, time, type, message', 'safe', 'on' => 'search'),
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
			'time' => 'Time',
			'type' => 'Type',
			'message' => 'Message',
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
		$criteria->with = array('user');
		$criteria->compare('id', $this->id);
		$criteria->compare('user', $this->user);
		$criteria->compare('time', $this->time);
		$criteria->compare('type', $this->type, true);
		$criteria->compare('message', $this->message, true);
		$sort = new CSort;
		$sort->attributes = array(
			'user' => array(
				'asc' => 'user.username ASC',
				'desc' => 'user.username DESC',
			),
			'time' => array(
				'asc' => 'time ASC',
				'desc' => 'time DESC',
			),
			'type' => array(
				'asc' => 'type ASC',
				'desc' => 'type DESC',
			),
			'message' => array(
				'asc' => 'message ASC',
				'desc' => 'message DESC',
			),
		);

		return new CActiveDataProvider($this, array(
					'criteria' => $criteria,
					'sort' => $sort
				));
	}
	
	static public function createLog($message, $type = 'info',$class='')
	{
		$log = new UserLogs;
		$log->user_id = Yii::app()->user->id;
		$log->message = $message;
		$log->time = time();
		$log->type = $type;
		$log->class = $class;
		if ($log->save())
		{
			return TRUE;
		}
		else
		{
			throw new CHttpException (CJSON::encode($log->errors));
		}
	}
	
	public static function decodeAttributes($attribute)
	{
		$return='';
		foreach($attribute as $key=>$value)
		{
			$return.=$key.":".$value;
		}
		return $return;
	}
}