<?php

/**
 * This is the model class for table "pickups".
 *
 * The followings are the available columns in table 'pickups':
 * @property string $id
 * @property string $pickup_date
 * @property integer $shipment_id
 *
 * The followings are the available model relations:
 * @property Shipments $shipment
 */
class Pickup extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Pickup the static model class
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
		return 'pickups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shipment_id', 'required'),
			array('shipment_id', 'numerical', 'integerOnly'=>true),
			array('pickup_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, pickup_date, shipment_id', 'safe', 'on'=>'search'),
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
			'shipment' => array(self::BELONGS_TO, 'Shipments', 'shipment_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pickup_date' => 'Pickup Date',
			'shipment_id' => 'Shipment',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('pickup_date',$this->pickup_date,true);
		$criteria->compare('shipment_id',$this->shipment_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeSave()
	{
		if($this->pickup_date != '')
		{
			$this->pickup_date = date('Y-m-d', strtotime($this->pickup_date));
		}
		return parent::beforeSave();
	}
}