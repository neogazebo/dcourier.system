<?php

/**
 * This is the model class for table "product_service".
 *
 * The followings are the available columns in table 'product_service':
 * @property integer $id
 * @property integer $product_id
 * @property string $name
 * @property integer $order
 * @property string $code
 * @property string $desc
 * @property string $weight
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property ProductServiceRate[] $productServiceRates
 */
class ProductService extends CActiveRecord
{
	const ProductCityCourier='1';
	const ProductDomestic='2';
	const ProductInternational='3';
	const ProductOther='4';
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductServices the static model class
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
		return 'product_service';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id', 'required'),
			array('id, product_id, order', 'numerical', 'integerOnly' => true),
			array('name', 'length', 'max' => 100),
			array('code', 'length', 'max' => 3),
			array('weight', 'length', 'max' => 12),
			array('desc', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, product_id, product_name, name, order, code, desc, weight', 'safe', 'on' => 'search'),
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
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
			'serviceDetail' => array(self::HAS_MANY, 'ServiceDetail', 'product_service_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_id' => 'Product',
			'product_name' => 'Product Name',
			'name' => 'Name',
			'order' => 'Order',
			'code' => 'Code',
			'desc' => 'Desc',
			'weight' => 'Weight',
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
		$criteria->compare('product_id', $this->product_id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('order', $this->order);
		$criteria->compare('code', $this->code, true);
		$criteria->compare('desc', $this->desc, true);
		$criteria->compare('weight', $this->weight, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'attributes' => array(
					'product_name' => array(
						'asc' => 'product.name',
						'desc' => 'product.name DESC',
					),
					'*',
				),
			),
		));
	}
	
	public static function getAvailableService($company_service_id)
	{
		$inCondition = array();
		$serviceCompany = RateCompanyService::model()->findByPk($company_service_id);
		
		if($serviceCompany->rateCompany->is_city)
			array_push ($inCondition, self::ProductCityCourier);
		if($serviceCompany->rateCompany->is_domestic)
			array_push ($inCondition, self::ProductDomestic);
		if($serviceCompany->rateCompany->is_international)
			array_push ($inCondition, self::ProductInternational);

		$criteria = new CDbCriteria;
		$criteria->addInCondition('product_id',$inCondition);
		
		return ProductService::model()->findAll($criteria);
	}
	public $product_name;

}