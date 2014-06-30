<?php

/**
 * This is the model class for table "view_report".
 *
 * The followings are the available columns in table 'view_report':
 * @property string $accountnr
 * @property string $name
 * @property string $awb
 * @property string $shipper_name
 * @property string $shipper_company_name
 * @property string $shipper_address
 * @property string $shipper_city
 * @property string $shipper_postal
 * @property string $shipper_country
 * @property string $shipper_phone
 * @property string $receiver_name
 * @property string $receiver_company_name
 * @property string $receiver_address
 * @property string $receiver_city
 * @property string $receiver_postal
 * @property string $receiver_country
 * @property string $receiver_phone
 * @property string $charges
 * @property integer $created
 * @property string $type
 * @property string $package_weight
 * @property string $package_value
 * @property integer $pieces
 * @property integer $shipping_status
 * @property string $shipping_charges
 * @property string $payer
 * @property string $pay_by
 * @property string $recipient_name
 * @property string $recipient_title
 * @property integer $recipient_date
 * @property string $destination_code
 * @property string $service_type
 * @property string $origin_code
 * @property string $service_code
 * @property string $goods_desc
 * @property string $vendor_charge
 * @property string $carrier_awb
 * @property string $carrier_name
 * @property integer $id
 */
class ViewReport extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ViewReport the static model class
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
		return 'view_report';
	}
	
	public function getPrimaryKey()
	{
		return $this->id;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shipper_company_name, receiver_company_name, package_value, pieces, destination_code, origin_code, service_code, goods_desc, vendor_charge, carrier_awb', 'required'),
			array('created, pieces, shipping_status, recipient_date, customer_id, id', 'numerical', 'integerOnly'=>true),
			array('accountnr, shipper_name, shipper_address, receiver_name, receiver_address, recipient_name, recipient_title, carrier_name', 'length', 'max'=>255),
			array('name, awb, shipper_city, receiver_city, carrier_awb', 'length', 'max'=>80),
			array('shipper_company_name, receiver_company_name', 'length', 'max'=>100),
			array('shipper_postal, receiver_postal', 'length', 'max'=>5),
			array('shipper_country, receiver_country', 'length', 'max'=>45),
			array('shipper_phone, receiver_phone, pay_by', 'length', 'max'=>30),
			array('charges, package_weight, package_value, shipping_charges, vendor_charge', 'length', 'max'=>12),
			array('type', 'length', 'max'=>8),
			array('payer', 'length', 'max'=>10),
			array('destination_code, origin_code, service_code', 'length', 'max'=>4),
			array('service_type', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('accountnr, name, awb, shipper_name, shipper_company_name, shipper_address, shipper_city, shipper_postal, shipper_country, shipper_phone, receiver_name, receiver_company_name, receiver_address, receiver_city, receiver_postal, receiver_country, receiver_phone, charges, created, type, package_weight, package_value, pieces, shipping_status, shipping_charges, payer, pay_by, recipient_name, recipient_title, recipient_date, destination_code, service_type, origin_code, service_code, goods_desc, vendor_charge, carrier_awb, carrier_name, id', 'safe', 'on'=>'search'),
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
			'accountnr' => 'Customer Account',
			'awb' => 'Waybill Number',
			'shipper_name' => 'Shipper Name',
			'shipper_company_name' => 'Shipper Company Name',
			'shipper_address' => 'Shipper Address',
			'shipper_city' => 'Shipper City',
			'shipper_postal' => 'Shipper Postal',
			'shipper_country' => 'Shipper Country',
			'shipper_phone' => 'Shipper Phone',
			'receiver_name' => 'Receiver Name',
			'receiver_company_name' => 'Receiver Company Name',
			'receiver_address' => 'Receiver Address',
			'receiver_city' => 'Receiver City',
			'receiver_postal' => 'Receiver Postal',
			'receiver_country' => 'Receiver Country',
			'receiver_phone' => 'Receiver Phone',
			'charges' => 'Charges',
			'created' => 'Created',
			'type' => 'Type',
			'package_weight' => 'Package Weight',
			'package_value' => 'Package Value',
			'pieces' => 'Pieces',
			'shipping_status' => 'Shipping Status',
			'shipping_charges' => 'Shipping Charges',
			'payer' => 'Payer',
			'pay_by' => 'Pay By',
			'recipient_name' => 'Recipient Name',
			'recipient_title' => 'Recipient Title',
			'recipient_date' => 'Recipient Date',
			'destination_code' => 'Destination Code',
			'service_type' => 'Service Type',
			'origin_code' => 'Origin Code',
			'service_code' => 'Service Code',
			'goods_desc' => 'Goods Desc',
			'vendor_charge' => 'Vendor Charge',
			'carrier_awb' => 'Carrier Waybill',
			'carrier_name' => 'Carrier Name',
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

		$criteria->compare('accountnr',$this->accountnr,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('awb',$this->awb,true);
		$criteria->compare('shipper_name',$this->shipper_name,true);
		$criteria->compare('shipper_company_name',$this->shipper_company_name,true);
		$criteria->compare('shipper_address',$this->shipper_address,true);
		$criteria->compare('shipper_city',$this->shipper_city,true);
		$criteria->compare('shipper_postal',$this->shipper_postal,true);
		$criteria->compare('shipper_country',$this->shipper_country,true);
		$criteria->compare('shipper_phone',$this->shipper_phone,true);
		$criteria->compare('receiver_name',$this->receiver_name,true);
		$criteria->compare('receiver_company_name',$this->receiver_company_name,true);
		$criteria->compare('receiver_address',$this->receiver_address,true);
		$criteria->compare('receiver_city',$this->receiver_city,true);
		$criteria->compare('receiver_postal',$this->receiver_postal,true);
		$criteria->compare('receiver_country',$this->receiver_country,true);
		$criteria->compare('receiver_phone',$this->receiver_phone,true);
		$criteria->compare('charges',$this->charges,true);
		$criteria->compare('created',$this->created);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('package_weight',$this->package_weight,true);
		$criteria->compare('package_value',$this->package_value,true);
		$criteria->compare('pieces',$this->pieces);
		$criteria->compare('shipping_status',$this->shipping_status);
		$criteria->compare('shipping_charges',$this->shipping_charges,true);
		$criteria->compare('payer',$this->payer,true);
		$criteria->compare('pay_by',$this->pay_by,true);
		$criteria->compare('recipient_name',$this->recipient_name,true);
		$criteria->compare('recipient_title',$this->recipient_title,true);
		$criteria->compare('recipient_date',$this->recipient_date);
		$criteria->compare('destination_code',$this->destination_code,true);
		$criteria->compare('service_type',$this->service_type,true);
		$criteria->compare('origin_code',$this->origin_code,true);
		$criteria->compare('service_code',$this->service_code,true);
		$criteria->compare('goods_desc',$this->goods_desc,true);
		$criteria->compare('vendor_charge',$this->vendor_charge,true);
		$criteria->compare('carrier_awb',$this->carrier_awb,true);
		$criteria->compare('carrier_name',$this->carrier_name,true);
		$criteria->compare('id',$this->id);
		$criteria->compare('customer_id',$this->customer_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}