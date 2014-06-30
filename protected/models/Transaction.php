<?php

/**
 * This is the model class for table "transaction".
 *
 * The followings are the available columns in table 'transaction':
 * @property integer $id
 * @property integer $shipment_id
 * @property integer $customer_id
 * @property integer $created
 * @property string $title
 * @property string $total
 * @property integer $invoice_id
 * @property integer $discount
 */
class Transaction extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Transaction the static model class
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
		return 'transaction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shipment_id, customer_id, created, invoice_id,discount', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('total,charges', 'length', 'max'=>12),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, shipment_id, customer_id, created, title, total, invoice_id', 'safe', 'on'=>'search'),
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
			'shipment_id' => 'Shipment',
			'customer_id' => 'Customer',
			'created' => 'Created',
			'title' => 'Title',
			'total' => 'Total',
			'invoice_id' => 'Invoice',
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
		$criteria->compare('shipment_id',$this->shipment_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('created',$this->created);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('total',$this->total,true);
		$criteria->compare('charges',$this->charges,true);
		$criteria->compare('invoice_id',$this->invoice_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getTotalChargesNDiscount($service_type,$service_id,$customer_id)
	{
		$discount = CustomerDiscount::model()->findByAttributes(array('service_type' => $service_type,'service_id' => $service_id,'customer_id' => $customer_id));
		if(!($discount instanceof CustomerDiscount))
			return array($this->charges,0);
		else
			return array($this->charges - ($this->charges * $discount->discount / 100),$discount->discount);
	}
	
	public function getShipmentData()
	{
		return Shipment::model()->findByPk($this->shipment_id);
	}
	
	public function getShipmentCharges()
	{
		$charges = Shipment::model()->findByPk($this->shipment_id)->getAllShipmentAdditionalCharges();
		$arr_charge = array('name' => 'Shipment Cost' ,'cost' => $this->charges);
		array_push($charges, $arr_charge);
		
		return $charges;
	}
}