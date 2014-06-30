<?php

/**
 * This is the model class for table "customer_discount".
 *
 * The followings are the available columns in table 'customer_discount':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $service_id
 * @property integer $discount
 * @property integer $created
 * @property integer $updated
 * @property string $harga_invoice
 * @property integer $discount_api
 * @property string $harga_api
 * @property integer $use_rate
 * @property integer $show_in_api
 * @property integer $vendor_discount
 *
 * The followings are the available model relations:
 * @property Customers $customer
 */
class CustomerDiscount extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return CustomerDiscount the static model class
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
		return 'customer_discount';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
//			array('customer_id,discount', 'required'),
			array('customer_id, service_id, discount, created, updated, discount_api, use_rate, show_in_api, vendor_discount', 'numerical', 'integerOnly'=>true),
			array('harga_invoice, harga_api', 'numerical'),
			array('discount,discount_api', 'numerical', 'max' => 100),
			array('harga_invoice, harga_api', 'length', 'max'=>12),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, customer_id, service_id, discount, created, updated, harga_invoice, discount_api, harga_api, use_rate, show_in_api, vendor_discount', 'safe', 'on'=>'search'),
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
			'customer_id' => 'Customer',
			'service_id' => 'Service',
			'discount' => 'Discount',
			'created' => 'Created',
			'updated' => 'Updated',
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
		$criteria->compare('customer_id', $this->customer_id);
		$criteria->compare('service_id', $this->service_id);
		$criteria->compare('discount', $this->discount);
		$criteria->compare('created', $this->created);
		$criteria->compare('updated', $this->updated);

		return new CActiveDataProvider($this, array(
					'criteria' => $criteria,
				));
	}

	public static function getDiscountTextField($customer_id, $service_id, $row)
	{
		$model = self::model()->findByAttributes(array(
			'customer_id' => $customer_id,
			'service_id' => $service_id,
				));

		$hidden_id = '';

		if (!($model instanceof CustomerDiscount))
			$model = new CustomerDiscount;
		else
			$hidden_id = CHtml::hiddenField('CustomerDiscount[' . $row . '][id]', $model->id);

		$hidden_customer_id = CHtml::hiddenField('CustomerDiscount[' . $row . '][customer_id]', $customer_id);
		$hidden_service_id = CHtml::hiddenField('CustomerDiscount[' . $row . '][service_id]', $service_id);
		$textField = CHtml::activeTextField($model, 'discount', array(
					'size' => 3,
					'maxlength' => 5,
					'name' => str_replace('CustomerDiscount', 'CustomerDiscount[' . $row . ']', CHtml::activeName($model, 'discount')),
				));

		return CHtml::tag('span', array('class' => 'row'), $hidden_customer_id . $hidden_service_id . $textField . $hidden_id);
	}

	public static function getTextField($customer_id, $service_id, $row, $field, $company_id, $percent=FALSE, $width = 10, $maxLength = 10)
	{
		$model = self::model()->findByAttributes(array(
			'customer_id' => $customer_id,
			'service_id' => $service_id,
				));

		$hidden_id = '';

		if (!($model instanceof CustomerDiscount))
			$model = new CustomerDiscount;
		else
			$hidden_id = CHtml::hiddenField('CustomerDiscount[' . $row . '][id]', $model->id);
		/**
		 * 5 company_id utk kirim.co.id
		 */
		if(!$percent && $company_id != 5)
		{
			$textField = CHtml::activeTextField($model, $field, array(
				'size' => $width,
				'disabled'=>'disabled',
				'maxlength' => $maxLength,
				'name' => str_replace('CustomerDiscount', 'CustomerDiscount[' . $row . ']', CHtml::activeName($model, $field)),
			));
		}
		else
		{
			$textField = CHtml::activeTextField($model, $field, array(
				'size' => $width,
				'maxlength' => $maxLength,
				'name' => str_replace('CustomerDiscount', 'CustomerDiscount[' . $row . ']', CHtml::activeName($model, $field)),
			));
		}

		return CHtml::tag('span', array('class' => 'row'), $textField);
	}

	public static function getCheckbox($customer_id, $service_id, $row, $field)
	{
		$model = self::model()->findByAttributes(array(
			'customer_id' => $customer_id,
			'service_id' => $service_id,
				));

		$hidden_id = '';

		if (!($model instanceof CustomerDiscount))
			$model = new CustomerDiscount;
		else
			$hidden_id = CHtml::hiddenField('CustomerDiscount[' . $row . '][id]', $model->id);

		$textField = CHtml::activeCheckBox($model, $field, array(
					'name' => str_replace('CustomerDiscount', 'CustomerDiscount[' . $row . ']', CHtml::activeName($model, $field)),
				));

		return CHtml::tag('span', array('class' => 'row'), $textField);
	}

	protected function beforeSave()
	{
		if (!$this->isNewRecord)
			$this->updated = time();
		else
			$this->created = time();
		return parent::beforeSave();
	}
	
	public static function getCustomerDiscountRate($service_id,$customer_id)
	{
		$criteria = new CDbCriteria;
		$criteria->join = 'JOIN service_detail t2 on t2.id = t.service_id JOIN rate_company_service t3 on t3.id = t2.rate_company_service_id';
		$criteria->select = 't.discount, t.harga_invoice, t.vendor_discount';
		$criteria->condition = 't3.id =:service_id AND t.customer_id =:customer_id';
		$criteria->params = array(':service_id' => $service_id,':customer_id' => $customer_id);
		$builder=new CDbCommandBuilder(Yii::app()->db->Schema);
	  $command=$builder->createFindCommand('customer_discount', $criteria);
	  $qa=$command->queryRow();
		return $qa;
	}
}