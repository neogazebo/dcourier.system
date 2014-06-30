<?php

/**
 * This is the model class for table "sure_charge".
 *
 * The followings are the available columns in table 'sure_charge':
 * @property integer $id
 * @property string $service_code
 * @property string $name
 * @property integer $rate
 * @property string $unit_charge
 * @property integer $is_fixed_rate
 */
class SureCharge extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SureCharge the static model class
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
		return 'sure_charge';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rate, is_fixed_rate', 'numerical', 'integerOnly'=>true),
			array('service_code', 'length', 'max'=>3),
			array('name, unit_charge', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, service_code, name, rate, unit_charge, is_fixed_rate', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'service_code' => 'Service Code',
			'name' => 'Name',
			'rate' => 'Rate',
			'unit_charge' => 'Unit Charge',
			'is_fixed_rate' => 'Is Fixed Rate',
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
		$criteria->compare('service_code',$this->service_code,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('rate',$this->rate);
		$criteria->compare('unit_charge',$this->unit_charge,true);
		$criteria->compare('is_fixed_rate',$this->is_fixed_rate);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}