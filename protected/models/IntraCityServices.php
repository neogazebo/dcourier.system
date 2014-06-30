<?php

/**
 * This is the model class for table "intra_city_service".
 *
 * The followings are the available columns in table 'intra_city_service':
 * @property integer $id
 * @property integer $area_id
 * @property integer $type_id
 * @property string $price
 * @property integer $weight
 *
 * The followings are the available model relations:
 * @property IntraCityType $type
 * @property IntraCityArea $area
 */
class IntraCityServices extends CActiveRecord
{
	private $_service;

	/**
	 * Returns the static model of the specified AR class.
	 * @return IntraCityServices the static model class
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
		return 'intra_city_service';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('area_id, type_id', 'required'),
			array('area_id, type_id, weight', 'numerical', 'integerOnly' => true),
			array('area_id, type_id, weight', 'uniqueCombinations'),
			array('price', 'length', 'max' => 12),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, area_id, type_id, price, weight', 'safe', 'on' => 'search'),
		);
	}

	public function uniqueCombinations($attribute, $params)
	{
		if (!$this->hasErrors())
		{
			$this->_service = IntraCityServices::model()->findByAttributes(array('area_id' => $this->area_id,
				'type_id' => $this->type_id, 'weight' => $this->weight));
			if (($this->_service instanceof IntraCityServices))
			{
				$this->addError('area_id', 'Area, jenis service dan berat sudah ada');
				$this->addError('type_id', 'Area, jenis service dan berat sudah ada');
				$this->addError('weight', 'Area, jenis service dan berat sudah ada');
			}
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'type' => array(self::BELONGS_TO, 'IntraCityTypes', 'type_id'),
			'area' => array(self::BELONGS_TO, 'IntraCityAreas', 'area_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'area_id' => 'Area',
			'type_id' => 'Service',
			'price' => 'Price',
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

		$criteria->with = array('area', 'type');
		$criteria->compare('id', $this->id);
		$criteria->compare('area_id', $this->area_id);
		$criteria->compare('type_id', $this->type_id);
		$criteria->compare('price', $this->price, true);
		$criteria->compare('weight', $this->weight);
		$criteria->compare('area', $this->area);
		$criteria->compare('type', $this->type);

		return new CActiveDataProvider($this, array(
					'criteria' => $criteria,
				));
	}

	public static function getServices($weight, $area_id)
	{
		$services = array();
		$rates = IntraCityServices::model()->findAllByAttributes(array('weight' => ceil($weight), 'area_id' => $area_id));

		foreach ($rates as $rate)
		{
			$service = array(
				'id' => $rate->id,
				'area_id' => $rate->area_id,
				'area' => $rate->area->name,
				'type_id' => $rate->type_id,
				'type' => $rate->type->name,
				'price' => $rate->price
			);

			array_push($services, $service);
		}
		return $services;
	}

	public static function getRates($type_id, $area_id, $weight)
	{
		$ratePrice = IntraCityServices::model()->findByAttributes(array('type_id' => $type_id, 'area_id' => $area_id, 'weight' => $weight));

		if (!($ratePrice instanceof IntraCityServices))
			return 0;

		return $ratePrice->price;
	}
}