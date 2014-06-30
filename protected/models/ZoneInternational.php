<?php

/**
 * This is the model class for table "zone_international".
 *
 * The followings are the available columns in table 'zone_international':
 * @property integer $id
 * @property string $country
 * @property string $zone
 */
class ZoneInternational extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ZoneInternational the static model class
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
		return 'zone_international';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('zone,country','required'),
			array('country', 'length', 'max'=>225),
			array('zone', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, country, zone', 'safe', 'on'=>'search'),
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
			'country' => 'Country',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('zone',$this->zone,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function zoneNames()
	{
		return array(
			'zone_a' => 'A',
			'zone_b' => 'B',
			'zone_c' => 'C',
			'zone_d' => 'D',
			'zone_e' => 'E',
			'zone_f' => 'F',
			'zone_g' => 'G',
		);
	}
	
	public static function getAllZoneCuntryData($rawCountry)
	{
		$criteria =	new CDbCriteria;
		$criteria->select = '*';
		$criteria->addSearchCondition('country', $rawCountry, true, 'AND', $rawCountry = 'LIKE');
		
		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$command = $builder->createFindCommand('zone_international', $criteria);
		$country_datas = $command->queryAll();
		
		$data = array();
		foreach ($country_datas as $country_data)
		{
			$data[] = array(
				'id' => $country_data['id'],
				'value' => ucfirst($country_data['country']),
				'label' => ucfirst($country_data['country']),
				'zone' => $country_data['zone']
			);
		}
		
		return $data;
	}
	
	public static function getZoneCountryData($country)
	{
		$criteria =	new CDbCriteria;
		$criteria->select = 'zone';
		$criteria->compare('country', ucfirst($country),'AND',FALSE);
		
		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$command = $builder->createFindCommand('zone_international', $criteria);
		$data = $command->queryRow();
		
		return $data;
	}
}