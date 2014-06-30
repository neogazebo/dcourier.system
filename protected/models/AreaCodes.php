<?php

/**
 * This is the model class for table "area_code".
 *
 * The followings are the available columns in table 'area_code':
 * @property string $code
 * @property string $city
 * @property string $main_city_code
 * @property string $location
 */
class AreaCodes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AreaCodes the static model class
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
		return 'area_code';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, city', 'required'),
			array('code, main_city_code', 'length', 'max'=>3),
			array('city, location', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('code, city, main_city_code, location', 'safe', 'on'=>'search'),
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
			'code' => 'Code',
			'city' => 'City',
			'main_city_code' => 'Main City Code',
			'location' => 'Location',
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

		$criteria->compare('code',$this->code,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('main_city_code',$this->main_city_code,true);
		$criteria->compare('location',$this->location,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}