<?php

/**
 * This is the model class for table "customers".
 *
 * The followings are the available columns in table 'customers':
 * @property integer $id
 * @property string $name
 * @property string $accountnr
 * @property string $numberID
 * @property string $comments
 * @property string $type
 * @property integer $billing_cycle
 * @property integer $notification_sms
 * @property integer $notification_email
 * @property string $token
 * @property integer $created
 * @property integer $updated
 * 
 * The followings are the available model relations:
 * @property contacts[] $customerContacts
 * @property departments[] $customerDepartments
 * @property Shipments[] $shipments
 */
class Customer extends CActiveRecord
{
	/**
	 * for searching to Contact models
	 */
	public $search_email;
	public $search_phone;
	public $change_password = false;
	public $new_password;
	public $new_password_confirmation;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Customer the static model class
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
		return 'customers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'length', 'max' => 80),
			array('accountnr,password', 'length', 'max' => 255),
			array('numberID,username', 'length', 'max' => 100),
			array('sales,created, updated', 'numerical', 'integerOnly' => true),
			array('type', 'length', 'max' => 10),
			array('name', 'required'),
			array('new_password_confirmation', 'compare', 'compareAttribute' => 'new_password', 'message' => 'Password not machced', 'on' => 'update'),
			array('comments,token,auth_key,search_email,search_phone,type,username,password,is_allow_api,new_password_confirmation,new_password', 'safe'),
			array('billing_cycle, notification_sms, notification_email', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, accountnr, comments, type', 'safe', 'on' => 'search'),
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
			'contacts' => array(self::HAS_MANY, 'CustomerContact', 'customer_id'),
			'departments' => array(self::HAS_MANY, 'CustomerDepartment', 'customer_id'),
			'shipments' => array(self::HAS_MANY, 'Shipments', 'customer_id'),
			'rekening' => array(self::HAS_MANY, 'CustomerRekening', 'customer_id'),
			'sales_territory' => array(self::BELONGS_TO, 'SalesTerritory', 'sales')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('model', 'ID'),
			'name' => Yii::t('model', 'Name'),
			'accountnr' => Yii::t('model', 'Account Number'),
			'comments' => Yii::t('model', 'Comments'),
			'type' => Yii::t('model', 'Type'),
			'billing_cycle' => Yii::t('model', 'Billing Cycle'),
			'notification_sms' => Yii::t('model', 'Sms Notification'),
			'notification_email' => Yii::t('nodel', 'Email Notification'),
			'auth_key' => Yii::t('model', 'Authentication Key'),
			'numberID' => Yii::t('model', 'ID Number'),
			'created' => Yii::t('model', 'created'),
			'updated' => Yii::t('model', 'updated'),
			'search_email' => Yii::t('model', 'Email'),
			'search_phone' => Yii::t('model', 'Phone'),
			'username' => Yii::t('model', 'Username'),
			'password' => Yii::t('model', 'Password'),
			'is_allow_api' => Yii::t('model', 'Is Allowed API'),
			'sales' => Yii::t('model', 'Sales Territory')
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

		$criteria->compare('name', $this->name, true);
		$criteria->compare('accountnr', $this->accountnr, true);
		$criteria->compare('type', $this->type, true);
		$criteria->compare('t2.email', $this->search_email, true);
		$criteria->compare('t2.phone1', $this->search_phone, true);

		$criteria->join = 'JOIN contacts t2 ON t2.parent_id = t.id AND t2.parent_model=\'Customer\'';

		return new CActiveDataProvider($this, array(
					'criteria' => $criteria,
				));
	}

	public function listType()
	{
		return array(
			'personal' => 'Personal',
			'coprate' => 'Corporate',
			'retail' => 'Retail'
		);
	}

	protected function beforeSave()
	{
		if (!$this->isNewRecord)
		{
			if ($this->new_password != '' || !empty($this->new_password))
			{
				$this->password = Yii::app()->hasher->hashPassword($this->new_password);
			}
		}
		else
		{
//			if($this->getScenario() != 'api')
//				$this->accountnr = $this->generateAccountNumber();

			$this->created = time();
		}
		return parent::beforeSave();
	}

	public function generateAccountNumber()
	{
		$criteria = new CDbCriteria;
		$criteria->select = 'created';

		$criteria->addCondition('YEAR(FROM_UNIXTIME(t.created)) = ' . date('Y'));
		$criteria->addCondition('MONTH(FROM_UNIXTIME(t.created)) = ' . date('m'));

		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$command = $builder->createFindCommand('customers', $criteria);
		$customer_count = count($command->queryAll());

		if ($this->type == 'personal')
			$digit1 = '2';
		else if ($this->type == 'company')
			$digit1 = '4';
		else
			return NULL;

		return $digit1 . date('m') . date('y') . str_pad($customer_count + 1, 4, "0", STR_PAD_LEFT);
	}

	public function suggest($keyword, $to_search)
	{
		if ($to_search == 'name')
			$condition = 'UPPER(' . $to_search . ') LIKE :keyword';
		else if ($to_search == 'accountnr')
			$condition = $to_search . ' LIKE :keyword';

		$res = $this->findAll(array(
			'condition' => $condition,
			'order' => 'name DESC',
			'params' => array(
				':keyword' => '%' . strtr($keyword, array('%' => '\%', '_' => '\_', '\\' => '\\\\')) . '%',
			),
				));

		$data = array();
		foreach ($res as $dest)
		{
			$data[] = array(
				'value' => $dest->accountnr.' / '.$dest->name,
				'label' => $dest->accountnr,
				'id' => $dest->id,
			);
		}
		return $data;
	}

	public static function getCustomerId()
	{
		$criteria = new CDbCriteria();
		$criteria->select = 'id';

		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$command = $builder->createFindCommand(Customer::model()->tableName(), $criteria);

		return $customers_id = $command->queryAll();
	}

	public function getContactData()
	{
		return Contact::model()->findByAttributes(array('parent_model' => 'Customer', 'parent_id' => $this->id));
	}

	public static function getShipmentCustomerAccount($customer_id)
	{
		$customer = self::model()->findByPk($customer_id);
		if (($customer instanceof Customer))
		{
			return $customer->accountnr;
		}
	}
	/*
	 * buat generete password
	 */

	public function validatePassword($password)
	{
		return $this->password = Yii::app()->hasher->checkPassword($password, $this->password);
	}

	public function is_allow_api()
	{
		if ($this->is_allow_api)
			return 'Yes';
		else
			return 'No';
	}

	public function getSalesTerritory()
	{
		if (is_object($this->sales_territory))
			return $this->sales_territory->territory . ' / ' . ucfirst($this->sales_territory->user->username);
		else
			return '-';
	}
//	public function __set($name, $value)
//	{
//		if ($name === 'password')
//			$this->password_changed = true;
//		parent::__set($name, $value);
//	}
}