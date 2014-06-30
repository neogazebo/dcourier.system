<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property string $name
 * @property integer $order
 *
 * The followings are the available model relations:
 * @property ProductService[] $productServices
 */
class Product extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Products the static model class
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
		return 'product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
//			array('id', 'required'),
			array('id, order', 'numerical', 'integerOnly' => true),
			array('name', 'length', 'max' => 100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, order', 'safe', 'on' => 'search'),
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
			'productServices' => array(self::HAS_MANY, 'ProductService', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'order' => 'Order',
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
		$criteria->compare('name', $this->name, true);
		$criteria->compare('order', $this->order);

		return new CActiveDataProvider($this, array(
					'criteria' => $criteria,
				));
	}

	public static function getExtProducts($shipment)
	{
		$result = array();
		$products = self::model()->findAll();
		foreach ($products as $product)
		{
			array_push($result, array(
				'boxLabel' => $product->name,
				'name' => CHtml::activeName($shipment, 'service_type'),
				'id' => str_replace(" ", "-", $product->name),
				'inputValue' => $product->name,
				'width' => 150,
				'checked'=> ($shipment->service_type == $product->name)
			));
		}

		return CJSON::encode($result);
	}
}