<?php

/**
 * This is the model class for table "shipment_event".
 *
 * The followings are the available columns in table 'shipment_event':
 * @property string $id
 * @property integer $shipment_id
 * @property integer $created
 * @property string $title
 * @property string $description
 * @property integer $user_id
 * @property string $status
 * @property integer $remark
 * @property integer $event_time
 * @property integer $with_mde
 * @property string $shipment_list
 *
 * The followings are the available model relations:
 * @property Shipments $shipment
 * @property Users $user
 */
class ShipmentEvent extends CActiveRecord
{
	
	public $recipient_name;
	public $recipient_title;
	public $recipient_date;
	public $shipment_list;

	/**
	 * Returns the static model of the specified AR class.
	 * @return ShipmentEvent the static model class
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
		return 'shipment_event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shipment_id, user_id', 'required','on'=>'insert'),
			array('shipment_id', 'safe','on'=>'bulkinsert'),
			array('shipment_list','required','on'=>'bulkinsert'),
			array('shipment_id, user_id, with_mde', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('status', 'length', 'max'=>20),
			array('description,created,remark,recipient_name,recipient_title,recipient_date,event_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, shipment_id, created, title, description, user_id, status', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'status_name' => array(self::BELONGS_TO, 'ShipmentStatus','status'),
			'remark_name' => array(self::BELONGS_TO, 'ShipmentRemark','remark'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'shipment_id' => 'Shipment',
			'created' => 'Created',
			'title' => 'Title',
			'description' => 'Description',
			'user_id' => 'User',
			'status' => 'Status',
			'shipment_list'=>'List of Waybill'
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
		$criteria->compare('shipment_id',$this->shipment_id);
		$criteria->compare('created',$this->created);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeSave()
	{
		if ($this->isNewRecord)
		{
			$this->created = time();
			
			if($this->getScenario()!= 'order')
				$this->event_time = strtotime ($this->event_time);
			
		}
		return parent::beforeSave();
	}
}