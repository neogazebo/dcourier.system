<?php

/**
 * This is the model class for table "rate_company_service".
 *
 * The followings are the available columns in table 'rate_company_service':
 * @property integer $id
 * @property string $name
 * @property integer $created
 * @property integer $updated
 * @property integer $rate_company_id
 *
 * The followings are the available model relations:
 * @property RateCity[] $rateCities
 * @property RateCompany $rateCompany
 * @property RateInternational[] $rateInternationals
 * @property ServiceDetail[] $serviceDetails
 */
class RateCompanyService extends CActiveRecord
{
	const JNE_id = 1;
	public $good_types = array();

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RateCompanyService the static model class
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
		return 'rate_company_service';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rate_company_id', 'required'),
			array('created, updated, rate_company_id', 'numerical', 'integerOnly' => true),
			array('name', 'length', 'max' => 45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, created, updated, rate_company_id', 'safe', 'on' => 'search'),
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
			'rateCities' => array(self::HAS_MANY, 'RateCity', 'service_id'),
			'rateCompany' => array(self::BELONGS_TO, 'RateCompany', 'rate_company_id'),
			'rateInternationals' => array(self::HAS_MANY, 'RateInternational', 'service_id'),
			'serviceDetails' => array(self::HAS_MANY, 'ServiceDetail', 'rate_company_service_id'),
			'productService' => array(self::HAS_MANY,'ProductService',array('product_service_id' => 'id'),'through' => 'serviceDetails')
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
			'created' => 'Created',
			'updated' => 'Updated',
			'rate_company_id' => 'Rate Company',
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

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('created', $this->created);
		$criteria->compare('updated', $this->updated);
		$criteria->compare('rate_company_id', $this->rate_company_id);

		return new CActiveDataProvider($this, array(
					'criteria' => $criteria,
				));
	}

	public function behaviors()
	{
		return array('ESaveRelatedBehavior' => array(
				'class' => 'application.components.ESaveRelatedBehavior')
		);
	}
	
	public static function getJNEServiceId()
	{
		$ids = array();
		$db=Yii::app()->db->createCommand();

		$db->select('rcp.id');
		$db->from('rate_company rc');
		$db->join('rate_company_service rcp','rc.id=rcp.rate_company_id');
		$db->where('rc.id=:id',array(':id'=>self::JNE_id));
		
		$all = $db->queryAll();
		
		foreach ($all as $al)
		{
			array_push($ids, $al['id']);
		}
		return $ids;
	}
	
	public static function getAllCarriers()
	{
		$db=Yii::app()->db->createCommand();

		$db->select('id,name,code');
		$db->from('rate_company rc');
		$db->where('rc.id=:id',array(':id'=>self::JNE_id));
	}
}