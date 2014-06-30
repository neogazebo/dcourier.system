<?php

/**
 * This is the model class for table "district".
 *
 * The followings are the available columns in table 'district':
 * @property integer $id
 * @property string $name
 * @property integer $province_id
 * @property string $type
 *
 * The followings are the available model relations:
 * @property Province $province
 * @property Zone[] $zones
 */
class District extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return District the static model class
	 */
	
	public $listtype = array('kabupaten'=>'Kabupaten','kota'=>'Kota');

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'district';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('province_id,name,type', 'required'),
			array('province_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('type', 'length', 'max'=>9),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, province_id, type', 'safe', 'on'=>'search'),
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
			'province' => array(self::BELONGS_TO, 'Province', 'province_id'),
			'zones' => array(self::HAS_MANY, 'Zone', 'district_id'),
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
			'province_id' => 'Province',
			'type' => 'Type',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('province_id',$this->province_id);
		$criteria->compare('type',$this->type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
                
	}
        
	public function getName()
	{
		return str_replace(array('kabupaten','kota'),array('Kab.','Kota'),$this->type)." ".$this->name;
	}     
	
	public static function getListDistrict($to_search)
	{
		$criteria = new CDbCriteria;
		$criteria->select = 't.name as district_name';
		$criteria->addSearchCondition('t.name', $to_search);
		
		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$command = $builder->createFindCommand('district', $criteria);
		$data_districts = $command->queryAll();

		$data = array();
		foreach ($data_districts as $data_district)
		{
			$data[] = array(
				'value' => $data_district['district_name'],
				'label' => $data_district['district_name'],
			);
		}

		return $data;
	}
	
	public static function getListPostcode($area,$district)
	{
		$criteria = new CDbCriteria;
		$criteria->select = 't.name,t.postcode';
		$criteria->join = 'JOIN zone t2 ON t2.id = t.zone_id JOIN district t3 ON t3.id = t2.district_id';
		$criteria->condition = 't.name LIKE :sterm AND t3.name LIKE :district';
		$criteria->params = array(":sterm" => "%$area%",":district" => "%$district%");
		
		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$command = $builder->createFindCommand('area', $criteria);
		$data_postcodes = $command->queryAll();

		$data = array();
		foreach ($data_postcodes as $data_postcode)
		{
			$data[] = array(
				'value' => $data_postcode['postcode'],
				'label' => $data_postcode['name'],
			);
		}

		return $data;
	}
	
	public static function getDistrictRatePrice($zdid)
	{	
		$criteria = new CDbCriteria;
		
		$criteria->addCondition('id = '.$zdid);
			
		$dataProvider= new CActiveDataProvider('district', array(
				'criteria' => $criteria,
		));
		return $dataProvider;
	}
	
	public function getRatePriceTextField($attribute, $csid = '', $oid = '', $did = '', $getId = false)
	{
		$hidenFieldId = '';
		$hiddenZoneId = '';
		$hiddenDistrictId = '';

		$model = RateDomestic::model()->findByAttributes(
				array(
					'service_id' => $csid,
					'origin_id' => $oid,
					'zone_id' => 0,
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
			$hiddenZoneId = CHtml::hiddenField('RateDomestic['.$this->id.'][zone_id]', 0);
			$hiddenDistrictId = CHtml::hiddenField('RateDomestic['.$this->id.'][district_id]', $this->id);
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