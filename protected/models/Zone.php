<?php

/**
 * This is the model class for table "zone".
 *
 * The followings are the available columns in table 'zone':
 * @property integer $id
 * @property string $name
 * @property integer $district_id
 * @property integer $active
 *
 * The followings are the available model relations:
 * @property Area[] $areas
 * @property District $district
 */
class Zone extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return Zone the static model class
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
		return 'zone';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('district_id', 'required'),
			array('district_id, active', 'numerical', 'integerOnly' => true),
			array('name', 'length', 'max' => 255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, district_id, active', 'safe', 'on' => 'search'),
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
			'areas' => array(self::HAS_MANY, 'Area', 'zone_id'),
			'district' => array(self::BELONGS_TO, 'District', 'district_id'),
			'province' => array(self::HAS_ONE, 'Province', array('district.province_id' => 'id'), 'through' => 'district')
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
			'district_id' => 'District',
			'active' => 'Active',
		);
	}
	/*
	 * function to get list zones and its district and province
	 * used in autocomplete in rate_price management
	 */

	public static function getListZone($to_search,$pid = 0,$fr = FALSE)
	{
		$select = 't.id as zone_id, t.name as zone_name, district.type as district_type, district.name as district_name, district.id as district_id, province.name as province_name';
		$join = 'JOIN district ON t.district_id = district.id JOIN province ON province.id = district.province_id';

		$criteria = new CDbCriteria;
		$criteria->select = $select;
		$criteria->join = $join;
		$criteria->addSearchCondition('t.name', $to_search, true, 'AND', $district_to_search = 'LIKE');
		if($fr && $pid > 0)
			$criteria->addCondition ('province.id = '.$pid);
		
		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$command = $builder->createFindCommand('zone', $criteria);
		$data_zones = $command->queryAll();

		$data = array();
		foreach ($data_zones as $data_zone)
		{
			if(!$fr)
			{
				$data[] = array(
					'value' => $data_zone['zone_name'],
					'label' => $data_zone['province_name'] . ' > ' . $data_zone['district_type'] . ' ' . $data_zone['district_name'] . ' > ' . $data_zone['zone_name'],
					'zid' => $data_zone['zone_id'],
					'did' => $data_zone['district_id']
				);
			}
			else
			{
				$data[] = array(
					'value' => $data_zone['zone_name'],
					'label' => $data_zone['district_type'] . ' ' . $data_zone['district_name'] . ' > ' . $data_zone['zone_name'],
					'zid' => $data_zone['zone_id'],
					'did' => $data_zone['district_id']
				);
			}
		}

		return $data;
	}

	/**
	 *belum tentu dipakai :
	 * 
	 * @param type $province
	 * @param type $district
	 * @param type $postal 
	 */
	public static function getZoneIdOrDistrictId($province, $district, $postal)
	{
		if (empty($postal))
		{
			$criteria = new CDbCriteria;
			$criteria->select = 't.id';
			$criteria->join = 'JOIN district ON t.district_id = district.id JOIN province ON province.id = district.province_id';
			$criteria->addSearchCondition('district.name', $district, true, 'AND');
			$criteria->addSearchCondition('province.name', $province, true, 'AND');
			$criteria->limit = 1;
			$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
			$command = $builder->createFindCommand('zone', $criteria);
			;
			$zone_id = $command->queryAll();
		}
		else
			$zone_id = Area::model()->findByAttributes(array('postcode' => $postal));

		print_r($zone_id);
		exit;
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
		$criteria->compare('district_id', $this->district_id);
		$criteria->compare('active', $this->active);

		return new CActiveDataProvider($this, array(
					'criteria' => $criteria,
				));
	}

	public static function getZoneRatePrice($zid, $did, $mode)
	{
		$criteria = new CDbCriteria;

		if ($mode == 'zone')
			$criteria->addCondition('id = ' . $zid);
		else if ($mode == 'district')
			$criteria->addCondition('district_id = ' . $did);

		$dataProvider = new CActiveDataProvider('zone', array(
					'criteria' => $criteria,
				));
		return $dataProvider;
	}

	/**
	 * this function used to generate the text fied in the rate_price 
	 * 
	 * @param type $attribute
	 * @param type $csid
	 * @param type $oid
	 * @param type $zid
	 * @param type $did
	 * @param type $getId
	 * @return type 
	 */
	public function getRatePriceTextField($attribute, $csid = '', $oid = '', $zid = '', $did = '', $getId = false)
	{
		$hidenFieldId = '';
		$hiddenZoneId = '';
		$hiddenDistrictId = '';

		$model = RateDomestic::model()->findByAttributes(
				array(
					'service_id' => $csid,
					'origin_id' => $oid,
					'zone_id' => $zid,
					'district_id' => $did
				)
		);
		if (!$model)
			$model = new RateDomestic;
		else
		{
			$model->$attribute = str_replace('.00', '', $model->$attribute);
			if ($attribute == 'first_kg')
			{
				$hidenFieldId = CHtml::activeHiddenField($model, 'id', array(
							'rel' => 'RatePriceTextField',
							'name' => str_replace('RateDomestic', 'RateDomestic[' . $this->id . ']', CHtml::activeName($model, 'id')),
							'class' => 'RatePrice_' . $this->id,
						));
			}
		}
		
		if($attribute == 'next_kg')
		{
			$hiddenZoneId = CHtml::hiddenField('RateDomestic['.$this->id.'][zone_id]', $this->id);
			$hiddenDistrictId = CHtml::hiddenField('RateDomestic['.$this->id.'][district_id]', $this->district_id);
		}
		
		$transitWidth = $attribute == 'transit_time' ? 'style' : null;
		$transitWidthValue = $attribute == 'transit_time' ? 'width:50px' : null;
		
		if ($getId)
			return $model->primaryKey;
		else if (!$getId)
		{
			return CHtml::tag('span', array('class' => 'row'), CHtml::activeTextField($model, $attribute, array(
								'rel' => 'RatePriceTextField',
								'name' => str_replace('RateDomestic', 'RateDomestic[' . $this->id . ']', CHtml::activeName($model, $attribute)),
								'class' => 'RatePrice_' . $this->id,
								$transitWidth => $transitWidthValue,
							)) . $hidenFieldId . $hiddenZoneId . $hiddenDistrictId . CHtml::openTag('span', array('id' => CHtml::activeId($model, $attribute) . '_em', 'class' => 'RatePrice_' . $this->id)) . CHtml::closeTag('span'));
		}
	}
}