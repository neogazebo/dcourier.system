<?php

/**
 * This is the model class for table "shipment_intracity".
 *
 * The followings are the available columns in table 'shipment_intracity':
 * @property integer $shipment_id
 * @property integer $area_id
 * @property integer $type_id
 */
class ShipmentIntracity extends CActiveRecord
{
	public function primaryKey()
	{
		return 'shipment_id';
	}
	/**
	 * Returns the static model of the specified AR class.
	 * @return ShipmentIntracity the static model class
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
		return 'shipment_intracity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shipment_id, area_id, type_id', 'numerical', 'integerOnly'=>true),
			array('area_id','required','on'=>'api'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('shipment_id, area_id, type_id', 'safe', 'on'=>'search'),
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
			'shipment' => array(self::BELONGS_TO, 'Shipment', 'shipment_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'shipment_id' => 'Shipment',
			'area_id' => 'Area',
			'type_id' => 'Type',
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

		$criteria->compare('shipment_id',$this->shipment_id);
		$criteria->compare('area_id',$this->area_id);
		$criteria->compare('type_id',$this->type_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}