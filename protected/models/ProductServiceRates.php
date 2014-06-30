<?php

/**
 * This is the model class for table "product_service_rate".
 *
 * The followings are the available columns in table 'product_service_rate':
 * @property integer $id
 * @property string $area_code
 * @property integer $product_service_id
 *
 * The followings are the available model relations:
 * @property AreaCode $areaCode
 * @property ProductService $productService
 */
class ProductServiceRates extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductServiceRates the static model class
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
		return 'product_service_rate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, area_code, product_service_id', 'required'),
			array('id, product_service_id', 'numerical', 'integerOnly'=>true),
			array('area_code', 'length', 'max'=>3),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, area_code, product_service_id', 'safe', 'on'=>'search'),
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
			'areaCode' => array(self::BELONGS_TO, 'AreaCode', 'area_code'),
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
			'area_code' => 'Area Code',
			'product_service_id' => 'Product Service',
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
		$criteria->compare('area_code',$this->area_code,true);
		$criteria->compare('product_service_id',$this->product_service_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}