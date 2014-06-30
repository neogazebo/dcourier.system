<?php

/**
 * This is the model class for table "shipment_items".
 *
 * The followings are the available columns in table 'shipment_items':
 * @property string $id
 * @property string $title
 * @property string $amount
 * @property integer $shipment_id
 * @property integer $package_height
 * @property integer $package_length
 * @property integer $package_width
 * @property integer $package_weight
 * @property integer $package_weight_vol
 *
 * The followings are the available model relations:
 * @property Shipments $shipment
 */
class ShipmentItem extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ShipmentItem the static model class
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
		return 'shipment_items';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shipment_id', 'required','on' => 'create, update'),
			array('shipment_id, package_height, package_length, package_width, package_weight, package_weight_vol', 'numerical'),
			array('package_height, package_length, package_width, package_weight', 'atLeastOne'),
			array('title', 'length', 'max'=>255),
			array('amount', 'length', 'max'=>12),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, amount, shipment_id, package_height, package_length, package_width, package_weight, package_weight_vol', 'safe', 'on'=>'search'),
		);
	}
	
	public function atLeastOne($attribute, $params)
	{
		if(empty($this->package_weight) && (empty($this->package_height) || empty($this->package_length) || empty($this->package_width)))
		{
			$this->addError('package_weight', 'shipment package cannot be blank');
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
			'title' => 'Deskripsi',
			'amount' => 'Nilai Barang',
			'shipment_id' => 'Shipment',
			'package_height' => 'Package Height',
			'package_length' => 'Package Length',
			'package_width' => 'Package Width',
			'package_weight' => 'Berat Paket',
			'package_weight_vol' => 'Berat Vol Metric',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('shipment_id',$this->shipment_id);
		$criteria->compare('package_height',$this->package_height);
		$criteria->compare('package_length',$this->package_length);
		$criteria->compare('package_width',$this->package_width);
		$criteria->compare('package_weight',$this->package_weight);
		$criteria->compare('package_weight_vol',$this->package_weight_vol);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getWeightToCount()
	{
		$weight=$this->package_weight;
		$width=$this->package_width ? $this->package_width : 0;
		$height=$this->package_height ? $this->package_height : 0;
		$length=$this->package_length ? $this->package_length : 0;
		$volume = self::getVolume($height, $width,$length );
		
		if(!empty ($weight))
		{
			if ($volume > $weight) $weight_to_count = $volume;
			else $weight_to_count = $weight;
		}
		else
		{
			if($height == 0 && $length == 0 && $width == 0) $weight_to_count = 0;
			else $weight_to_count = $volume;
		}
		return $weight_to_count;
	}
	
	public static function getStaticWeightToCount($weight = 0, $height = 0, $width = 0, $length = 0)
	{	
		$volume = self::getVolume($height, $width,$length );
		if(!empty ($weight))
		{
			if ($volume > $weight) $weight_to_count = $volume;
			else $weight_to_count = $weight;
		}
		else
		{
			if($height == 0 && $length == 0 && $width == 0) $weight_to_count = 0;
			else $weight_to_count = $volume;
		}
		return $weight_to_count;
	}


	public function beforeSave()
	{
		if(!empty($this->package_weight))
		{
			ceil($this->package_weight);
		}
		return parent::beforeSave();
	}
	
	public static function getVolume($height,$width,$length)
	{
		return ceil(($height * $width * $length) / 5000);
	}
}