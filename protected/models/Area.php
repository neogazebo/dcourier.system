<?php

/**
 * This is the model class for table "area".
 *
 * The followings are the available columns in table 'area':
 * @property integer $id
 * @property string $name
 * @property string $postcode
 * @property integer $zone_id
 *
 * The followings are the available model relations:
 * @property Zone $zone
 */
class Area extends CActiveRecord
{
	public $zone_name = '';

	/**
	 * Returns the static model of the specified AR class.
	 * @return Area the static model class
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
		return 'area';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, postcode', 'required'),
			array('zone_id', 'numerical', 'integerOnly' => true),
			array('name', 'length', 'max' => 255),
			array('postcode', 'length', 'max' => 5, 'on' => 'create'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, postcode, zone_id, district, province, zone', 'safe', 'on' => 'search'),
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
			'zone' => array(self::BELONGS_TO, 'Zone', 'zone_id'),
			'district' => array(self::HAS_ONE, 'District', array('zone.district_id' => 'id'), 'through' => 'zone'),
			'province' => array(self::HAS_ONE, 'Province', array('zone.district.province_id' => 'id'), 'through' => 'district')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('model', 'ID'),
			'name' => Yii::t('model', 'Name'),
			'postcode' => Yii::t('model', 'Postcode'),
			'zone_id' => Yii::t('model', 'Zone'),
			'zone' => Yii::t('model', 'Kecamatan - Kelurahan'),
			'district' => Yii::t('web', 'Kota/Kab'),
			'province' => Yii::t('web', 'Propinsi'),
			'postcode' => Yii::t('web', 'Kode Pos'),
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

		$unique = array();
		$criteria = new CDbCriteria;
		$criteria->group = 'postcode';
		$criteria->with = array('zone' => array('with' => array('district' => array('with' => 'province'))));
		$criteria->addSearchCondition('postcode', $this->postcode . '%', FALSE, 'AND', 'LIKE');
		$criteria->compare('t.name', $this->postcode, true, 'OR');
		$criteria->compare('zone.name', $this->postcode, true, 'OR');
		$criteria->compare('district.name', $this->district, true);
		$criteria->compare('province.name', $this->province, true);

		$sort = new CSort;
		$sort->attributes = array(
			'province' => array(
				'asc' => 'province.name',
				'desc' => 'province.name DESC'
			),
			'district' => array(
				'asc' => 'district.name',
				'desc' => 'district.name DESC'
			),
			'zone' => array(
				'asc' => 'zone.name',
				'desc' => 'zone.name DESC',
			),
			'postcode' => array(
				'asc' => 'postcode',
				'desc' => 'postcode DESC',
			),
		);

		return new CActiveDataProvider($this, array(
					'criteria' => $criteria,
					'sort' => $sort,
					'pagination' => array(
						'pageSize' => 100
					)
				));
	}

	public static function getPostCode($area)
	{
		if (is_numeric($area))
			return $area;

		$area = Area::model()->find('name=:name', array(':name' => $area));
		if (is_null($area))
			return null;
		return $area->postcode;
	}

	public function getZoneToolTips($postcode)
	{
		if (!$postcode)
			$postcode = $this->postcode;
		$areas = Yii::app()->db->createCommand()
				->select('*')
				->from('area')
				->where('postcode = ' . $postcode)
				->queryAll();
		foreach ($areas as $area)
		{
			$namaArea[$area['id']][Zone::model()->findByPk($area['zone_id'])->name] = $area['name'];
		}
		foreach ($namaArea as $value)
		{
			foreach ($value as $key => $value)
			{
				$returnArray[$key][] = $value;
			}
		}
		foreach ($returnArray as $key => $value)
		{
			$return[] = array($key => $value);
		}
		return $return;
	}
	
	public static function getZoneID($data,$mode = 'postcode')
	{
		$criteria=new CDbCriteria;
	  $criteria->select='t.zone_id,t2.district_id';
	  $criteria->join='JOIN zone t2 ON t2.id=t.zone_id JOIN district t3 ON t3.id = t2.district_id';
		if($mode == 'postcode')
			$criteria->addCondition('t.postcode = '.$data);
		elseif($mode=='city')
		{
			$criteria->condition = 't3.name = "'.$data.'" AND t3.type = "kota"';
//			$criteria->addCondition('t2.name = "'.$data.'"', 'OR');
		}
		
	  $builder=new CDbCommandBuilder(Yii::app()->db->Schema);
	  $command=$builder->createFindCommand('area', $criteria);
	  $result=$command->queryRow();
		
		return $result;
	}

}