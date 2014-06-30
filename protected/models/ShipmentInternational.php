<?php

/**
 * This is the model class for table "shipment_international".
 *
 * The followings are the available columns in table 'shipment_international':
 * @property integer $shipment_id
 * @property string $zone
 */
class ShipmentInternational extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ShipmentInternational the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function primaryKey()
	{
		return 'shipment_id';
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'shipment_international';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shipment_id', 'numerical', 'integerOnly'=>true),
			array('zone', 'length', 'max'=>45),
			array('zone','required','on'=>'api'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('shipment_id, zone', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'shipment_id' => 'Shipment',
			'zone' => 'Zone',
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
		$criteria->compare('zone',$this->zone,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}