<?php

/**
 * This is the model class for table "customer_shipping_profile".
 *
 * The followings are the available columns in table 'customer_shipping_profile':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $product_service_id
 * @property string $origin
 * @property string $destination
 * @property string $volume
 *
 * The followings are the available model relations:
 * @property Customers $customer
 * @property ProductService $productService
 */
class CustomerShippingProfile extends CActiveRecord
{
	public $product_id;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CustomerShippingProfile the static model class
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
		return 'customer_shipping_profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, product_service_id', 'required'),
			array('customer_id, product_service_id', 'numerical', 'integerOnly'=>true),
			array('origin', 'length', 'max'=>3),
			array('destination', 'length', 'max'=>45),
			array('volume', 'length', 'max'=>12),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, customer_id, product_service_id, origin, destination, volume', 'safe', 'on'=>'search'),
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
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
			'productService' => array(self::BELONGS_TO, 'ProductService', 'product_service_id'),
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
			'product_service_id' => 'Product Service',
			'origin' => 'Origin',
			'destination' => 'Destination',
			'volume' => 'Volume',
			'product_id'=>'Product Name'
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
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('product_service_id',$this->product_service_id);
		$criteria->compare('origin',$this->origin,true);
		$criteria->compare('destination',$this->destination,true);
		$criteria->compare('volume',$this->volume,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}