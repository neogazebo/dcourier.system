<?php

/**
 * This is the model class for table "token".
 *
 * The followings are the available columns in table 'token':
 * @property integer $id
 * @property string $token
 * @property integer $created
 * @property integer $lastaccess
 * @property integer $is_login
 * @property integer $customer_id
 *
 * The followings are the available model relations:
 * @property Customers $customer
 */
class Token extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Token the static model class
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
		return 'token';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('token, created, customer_id', 'required'),
			array('created, lastaccess, is_login, customer_id', 'numerical', 'integerOnly' => true),
			array('token', 'length', 'max' => 255),
			array('token', 'unique'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, token, created, lastaccess, is_login, customer_id', 'safe', 'on' => 'search,'),
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
			'customer' => array(self::BELONGS_TO, 'Customers', 'customer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'token' => 'Token',
			'created' => 'Created',
			'lastaccess' => 'Lastaccess',
			'is_login' => 'Is Login',
			'customer_id' => 'Customer',
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
		$criteria->compare('token', $this->token, true);
		$criteria->compare('created', $this->created);
		$criteria->compare('lastaccess', $this->lastaccess);
		$criteria->compare('is_login', $this->is_login);
		$criteria->compare('customer_id', $this->customer_id);

		return new CActiveDataProvider($this, array(
					'criteria' => $criteria,
				));
	}

	public static function create($customerId)
	{
		$customer = Customer::model()->findByPk($customerId);
		if ($customer == null)
			throw new CHttpException(400, 'Invalid request. Customer id not available.');
		$model = new Token;
		$model->token = Yii::app()->hasher->hashPassword(time() . $customer->accountnr);
		$model->created = time();
		$model->customer_id = $customerId;
		while (!$model->validate())
		{
			$model->token = Yii::app()->hasher->hashPassword(time() . $customer->accountnr);
		}
		if($model->save())
			return $model->token;
		return false;
	}

	public static function getCustomerId($token)
	{
		$token = Token::model()->findByAttributes(array('token' => $token));
		if ($token == null)
			throw new CHttpException(400, 'Invalid request. Token Failed.');
		return $token->customer_id;
	}

	public static function deleteByToken($token)
	{
		$token = Token::model()->findByAttributes(array('token' => $token));
		if ($token == null)
			throw new CHttpException(400, 'Invalid request. Token Failed.');
		if ($token->delete())
			return true;
		return false;
	}
}