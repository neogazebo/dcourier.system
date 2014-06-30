<?php

/**
 * This is the model class for table "shipments".
 *
 * The followings are the available columns in table 'shipments':
 * @property integer $id
 * @property string $awb
 * @property integer $customer_id
 * @property integer $service_id
 * @property string $shipper_name
 * @property string $shipper_company_name
 * @property string $shipper_address
 * @property string $shipper_city
 * @property string $shipper_postal
 * @property string $shipper_country
 * @property string $shipper_phone
 * @property string $shipper_email
 * @property string $shipper_numberID
 * @property string $shipper_comment
 * @property string $receiver_name
 * @property string $receiver_company_name
 * @property string $receiver_address
 * @property string $receiver_city
 * @property string $receiver_postal
 * @property string $receiver_country
 * @property string $receiver_phone
 * @property string $receiver_email
 * @property string $receiver_numberID
 * @property string $receiver_comment
 * @property string $charges
 * @property integer $created
 * @property integer $modified
 * @property string $type
 * @property string $package_weight
 * @property string $package_value
 * @property integer $pieces
 * @property integer $shipping_status
 * @property integer $shipping_remark
 * @property string $service_type
 * @property integer $insurance
 * @property string $fuel_charges
 * @property string $freight_charges
 * @property string $shipping_charges
 * @property string $vat
 * @property string $payer
 * @property string $pay_by
 * @property string $shipment_description
 * @property string $sr_number
 * @property string $delivery_date
 * @property string $commodity
 * @property string $packaging
 * @property string $remarks
 * @property string $special_service
 * @property string $recipient_name
 * @property string $recipient_title
 * @property integer $recipient_date
 * @property integer $event_time
 * @property string $cod
 * @property string $destination_code
 * @property string $origin_code
 * @property string $service_code
 * @property string $goods_desc
 * @property integer $fragile
 * @property string $vendor_charge
 * @property string $is_cod
 * @property string $booking_id
 * @property string $carrier_awb
 *
 * The followings are the available model relations:
 * @property OrderTracking[] $orderTrackings
 * @property Pickups[] $pickups
 * @property ShipmentAdditionalCharges[] $shipmentAdditionalCharges
 * @property ShipmentItems[] $shipmentItems
 */
class Shipment extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Shipment the static model class
	 */
	public $listtype = array('document' => 'Document', 'parcel' => 'Parcel');
	public $listpayer = array('shipper' => 'Shipper', 'consignee' => 'Consignee', '3rdparty' => '3rd Party');
	public $listpayby = array('cash' => 'Cash', 'credit_card' => 'Credit Card', 'account' => 'Account');
	public $listpickupaddresstype = array('office' => 'Office', 'residential' => 'Residential');
	public $listspecialservice = array('saturday delivery' => 'Saturday Delivery', 'remote area' => 'Remote Area');
	public $szones = array(
		1 => 'Jakarta',
	);
	public $rzones = array(
		1 => 'Jakarta',
		2 => 'Bandung',
		3 => 'Medan',
		4 => 'Surabaya',
		5 => 'Bali',
		6 => 'Semarang',
		7 => 'Padang',
		8 => 'Banda Aceh'
	);
	private $_postal_code;

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'shipments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('payer,service_code,service_type,insurance,shipper_address,shipper_city,shipper_postal,shipper_country,type,service_id', 'required', 'on' => 'insert,update'),
			array('shipper_email,receiver_email', 'required', 'on' => 'cekemail'),
			array('awb', 'unique', 'allowEmpty' => true),
			array('customer_id, service_id, created, modified, pieces, shipping_status, shipping_remark, insurance, is_cod, recipient_date, event_time, fragile, booking_id', 'numerical', 'integerOnly' => true),
			array('awb, carrier_awb, shipper_city, receiver_city', 'length', 'max' => 80),
			array('shipper_name, shipper_address, receiver_name, receiver_address, recipient_name, recipient_title', 'length', 'max' => 255),
			array('shipper_company_name, shipper_email, shipper_numberID, receiver_company_name, receiver_email, receiver_numberID, shipment_description, commodity, packaging, remarks', 'length', 'max' => 100),
			array('shipper_postal, receiver_postal', 'length', 'max' => 5),
			array('shipper_country, receiver_country', 'length', 'max' => 45),
			array('shipper_phone, receiver_phone, pay_by, special_service', 'length', 'max' => 30),
			array('charges, package_weight, package_value, fuel_charges, freight_charges, shipping_charges, vat, cod, vendor_charge', 'length', 'max' => 12),
			array('type', 'length', 'max' => 8),
			array('service_type', 'length', 'max' => 15),
			array('payer', 'length', 'max' => 10),
			array('sr_number', 'length', 'max' => 20),
			array('destination_code, origin_code, service_code', 'length', 'max' => 4, 'min' => 3),
			array('shipper_comment, receiver_comment, delivery_date, goods_desc', 'safe'),
			array('awb,service_type,insurance,shipper_address,shipper_city,shipper_postal,shipper_country,type,service_id', 'safe', 'on' => 'cek-rate'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, awb, customer_id, service_id, shipper_name, shipper_company_name, shipper_address, shipper_city, shipper_postal, shipper_country, shipper_phone, shipper_email, shipper_numberID, shipper_comment, receiver_name, receiver_company_name, receiver_address, receiver_city, receiver_postal, receiver_country, receiver_phone, receiver_email, receiver_numberID, receiver_comment, charges, created, modified, type, package_weight, package_value, pieces, shipping_status, shipping_remark, service_type, insurance, fuel_charges, freight_charges, shipping_charges, vat, payer, pay_by, shipment_description, sr_number, delivery_date, commodity, packaging, remarks, special_service, recipient_name, recipient_title, recipient_date, event_time, cod, destination_code, origin_code, service_code, goods_desc, fragile, vendor_charge', 'safe', 'on' => 'search'),
		);
	}

	public function getService_type()
	{
		return array(
			'city' => Yii::t('web', 'Intra City'),
			'domestic' => Yii::t('web', 'Domestik'),
			'international' => Yii::t('web', 'Internasional')
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
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
			'items' => array(self::HAS_MANY, 'ShipmentItem', 'shipment_id'),
			'additional_charges' => array(self::HAS_MANY, 'ShipmentAdditionalCharge', 'shipment_id'),
			'events' => array(self::HAS_MANY, 'ShipmentEvent', 'shipment_id'),
			'orderTrackings' => array(self::HAS_ONE, 'OrderTracking', 'shipments_id'),
			'shippingStatus' => array(self::BELONGS_TO, 'ShipmentStatus', 'shipping_status'),
			'pickup' => array(self::HAS_ONE, 'Pickup', 'shipment_id'),
			'shipmentAdditionalCharges' => array(self::HAS_MANY, 'ShipmentAdditionalCharges', 'shipment_id'),
			'shipmentItems' => array(self::HAS_MANY, 'ShipmentItems', 'shipment_id'),
			'booking' => array(self::BELONGS_TO, 'Booking', 'booking_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_id' => 'Customer ID',
			'customer_name' => 'Customer Name',
			'awb' => 'Waybill',
			'shipper_name' => 'Shipper Name',
			'shipper_address' => 'Address',
			'shipper_city' => 'City',
			'shipper_postal' => 'Postal/Zip Code',
			'shipper_country' => 'Country',
			'shipper_phone' => 'Phone',
			'receiver_name' => 'Receiver Name',
			'receiver_address' => 'Address',
			'receiver_city' => 'City',
			'receiver_postal' => 'Postal',
			'receiver_country' => 'Country',
			'receiver_phone' => 'Phone',
			'charges' => 'Charges',
			'created' => 'Created',
			'type' => 'Shipment Type',
			'package_weight' => 'Berat Total',
			'shipping_status' => 'Shipping Status',
			'charges' => 'Total Charges',
			'carrier_awb' => 'Carrier Waybill'
		);
	}

	/**
	 * 
	 * @return array
	 */
	public function reportLabel()
	{
		return array(
			'awb' => 'Awb',
			'customer_id' => 'Customer',
			'service_id' => 'Service',
			'shipper_name' => 'Shipper Name',
			'shipper_company_name' => 'Shipper Company Name',
			'shipper_address' => 'Shipper Address',
			'shipper_city' => 'Shipper City',
			'shipper_postal' => 'Shipper Postal',
			'shipper_country' => 'Shipper Country',
			'shipper_phone' => 'Shipper Phone',
			'shipper_email' => 'Shipper Email',
			'receiver_name' => 'Receiver Name',
			'receiver_company_name' => 'Receiver Company Name',
			'receiver_address' => 'Receiver Address',
			'receiver_city' => 'Receiver City',
			'receiver_postal' => 'Receiver Postal',
			'receiver_country' => 'Receiver Country',
			'receiver_phone' => 'Receiver Phone',
			'receiver_email' => 'Receiver Email',
			'charges' => 'Charges',
			'created' => 'Created',
			'type' => 'Type',
			'package_weight' => 'Package Weight',
			'package_value' => 'Package Value',
			'pieces' => 'Pieces',
			'shipping_status' => 'Shipping Status',
			'shipping_remark' => 'Shipping Remark',
			'service_type' => 'Service Type',
			'insurance' => 'Insurance',
			'shipping_charges' => 'Shipping Charges',
			'payer' => 'Payer',
			'pay_by' => 'Pay By',
			'delivery_date' => 'Delivery Date',
			'remarks' => 'Remarks',
			'recipient_name' => 'Recipient Name',
			'recipient_title' => 'Recipient Title',
			'recipient_date' => 'Recipient Date',
			'destination_code' => 'Destination Code',
			'origin_code' => 'Origin Code',
			'service_code' => 'Service Code',
			'goods_desc' => 'Goods Desc',
			'fragile' => 'Fragile',
			'vendor_charge' => 'Vendor Charge',
			'booking_id' => 'Booking',
			'carrier_awb' => 'Carrier Awb',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($up = null)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('awb', $this->awb, true);
		$criteria->compare('customer_id', $this->customer_id);
		$criteria->compare('service_id', $this->service_id);
		$criteria->compare('shipper_name', $this->shipper_name, true);
		$criteria->compare('shipper_company_name', $this->shipper_company_name, true);
		$criteria->compare('shipper_address', $this->shipper_address, true);
		$criteria->compare('shipper_city', $this->shipper_city, true);
		$criteria->compare('shipper_postal', $this->shipper_postal, true);
		$criteria->compare('shipper_country', $this->shipper_country, true);
		$criteria->compare('shipper_phone', $this->shipper_phone, true);
		$criteria->compare('shipper_email', $this->shipper_email, true);
		$criteria->compare('shipper_numberID', $this->shipper_numberID, true);
		$criteria->compare('shipper_comment', $this->shipper_comment, true);
		$criteria->compare('receiver_name', $this->receiver_name, true);
		$criteria->compare('receiver_company_name', $this->receiver_company_name, true);
		$criteria->compare('receiver_address', $this->receiver_address, true);
		$criteria->compare('receiver_city', $this->receiver_city, true);
		$criteria->compare('receiver_postal', $this->receiver_postal, true);
		$criteria->compare('receiver_country', $this->receiver_country, true);
		$criteria->compare('receiver_phone', $this->receiver_phone, true);
		$criteria->compare('receiver_email', $this->receiver_email, true);
		$criteria->compare('receiver_numberID', $this->receiver_numberID, true);
		$criteria->compare('receiver_comment', $this->receiver_comment, true);
		$criteria->compare('charges', $this->charges, true);
		$criteria->compare('created', $this->created);
		$criteria->compare('modified', $this->modified);
		$criteria->compare('type', $this->type, true);
		$criteria->compare('package_weight', $this->package_weight, true);
		$criteria->compare('package_value', $this->package_value, true);
		$criteria->compare('pieces', $this->pieces);
		$criteria->compare('shipping_status', $this->shipping_status);
		$criteria->compare('shipping_remark', $this->shipping_remark);
		$criteria->compare('service_type', $this->service_type, true);
		$criteria->compare('insurance', $this->insurance);
		$criteria->compare('fuel_charges', $this->fuel_charges, true);
		$criteria->compare('freight_charges', $this->freight_charges, true);
		$criteria->compare('shipping_charges', $this->shipping_charges, true);
		$criteria->compare('vat', $this->vat, true);
		$criteria->compare('payer', $this->payer, true);
		$criteria->compare('pay_by', $this->pay_by, true);
		$criteria->compare('shipment_description', $this->shipment_description, true);
		$criteria->compare('sr_number', $this->sr_number, true);
		$criteria->compare('delivery_date', $this->delivery_date, true);
		$criteria->compare('commodity', $this->commodity, true);
		$criteria->compare('packaging', $this->packaging, true);
		$criteria->compare('remarks', $this->remarks, true);
		$criteria->compare('special_service', $this->special_service, true);
		$criteria->compare('recipient_name', $this->recipient_name, true);
		$criteria->compare('recipient_title', $this->recipient_title, true);
		$criteria->compare('recipient_date', $this->recipient_date);
		$criteria->compare('event_time', $this->event_time);
		$criteria->compare('cod', $this->cod, true);
		$criteria->compare('destination_code', $this->destination_code, true);
		$criteria->compare('origin_code', $this->origin_code, true);
		$criteria->compare('service_code', $this->service_code, true);
		$criteria->compare('goods_desc', $this->goods_desc, true);
		$criteria->compare('fragile', $this->fragile);
		$criteria->compare('vendor_charge', $this->vendor_charge, true);
		$criteria->compare('booking_id', $this->booking_id, true);
		$criteria->order = 'id DESC';

		if ($up == 'cs-buk')
		{
			$criteria->addCondition('shipping_status = 1');
		}
		else if ($up == 'op-buk')
		{
			$criteria->addCondition('shipping_status = 1 || shipping_status = 11');
		}

		return new CActiveDataProvider($this, array(
					'criteria' => $criteria,
				));
	}

	protected function beforeSave()
	{
		if ($this->isNewRecord)
		{
			$this->created = time();
			$this->event_time = time();
			$this->shipping_status = 1;

//			ShipmentAdditionalCharge::initSurcharges($this);
		}
		else
		{
			if ($this->getScenario() != 'event')
			{
				$this->event_time = time();
			}
		}
		$this->delivery_date = date('Y-m-d', strtotime($this->delivery_date));

		return parent::beforeSave();
	}

	public function afterSave()
	{
		if ($this->isNewRecord)
		{
			//for the tracker
			$tracker = new OrderTracking('OrderCreated');
			$tracker->shipments_id = $this->primaryKey;
			$tracker->save();

			//for shipment event
			$shipment_event = new ShipmentEvent();
			$shipment_event->shipment_id = $this->id;
			$shipment_event->status = $this->shipping_status;
			$shipment_event->event_time = $this->event_time;
			if ($this->getScenario() == 'api-requestpickup')
				$shipment_event->user_id = 0;
			else
				$shipment_event->user_id = Yii::app()->user->id;
			$shipment_event->setScenario('order');
			$shipment_event->save();

			if (!empty($this->customer_id))
			{
				$trans = new Transaction;
				$trans->shipment_id = $this->id;
				$trans->customer_id = $this->customer_id;
				$trans->created = $this->created;
				$trans->charges = $this->shipping_charges;
				$trans->total = $this->charges;
				$trans->save();
			}
		}
		else
		{
			if ($this->getScenario() != 'event')
			{
				$shipment_event = new ShipmentEvent();
				$shipment_event->shipment_id = $this->id;
				$shipment_event->status = $this->shipping_status;
				$shipment_event->event_time = time();
				$shipment_event->user_id = Yii::app()->user->id;
				$shipment_event->with_mde = 1;
				$shipment_event->setScenario('order');
				$shipment_event->save();
			}

			if (!empty($this->customer_id))
			{
				$trans = Transaction::model()->findByAttributes(array('shipment_id' => $this->id));
				$trans->shipment_id = $this->id;
				$trans->customer_id = $this->customer_id;
				$trans->created = $this->created;
				$trans->charges = $this->shipping_charges;
				$trans->total = $this->charges;
				$trans->save();
			}
		}
	}

	public function getAllShipmentAdditionalCharges()
	{
		$all_charges = array();
		$add_charges = ShipmentAdditionalCharge::model()->findAllByAttributes(array('shipment_id' => $this->id), 'cost > 0');
		if (count($add_charges) > 0)
		{
			foreach ($add_charges as $charge)
				array_push($all_charges, array('name' => $charge->name, 'cost' => $charge->cost));
		}
		return $all_charges;
	}

	public static function getItemQty($shipment_id)
	{
		return ShipmentItem::model()->countByAttributes(array('shipment_id' => $shipment_id));
	}

	public static function getItemValue($shipment_id)
	{
		return Yii::app()->db->createCommand('SELECT sum(amount) FROM shipment_items WHERE shipment_id = ' . $shipment_id)->queryScalar();
	}

	public function getListPayerXtjs()
	{
		$listpayer = $this->listpayer;
		$result = array();
		foreach ($listpayer as $key => $val)
		{
			array_push($result, array(
				'boxLabel' => $val,
				'inputValue' => $key,
				'name' => CHtml::activeName(self::model(), 'payer'),
				'checked' => ($this->payer == $key)
			));
		}
		return CJSON::encode($result);
	}

	public function getListPayByXtjs()
	{
		$listpayby = $this->listpayby;
		$result = array();
		foreach ($listpayby as $key => $val)
		{
			array_push($result, array(
				'boxLabel' => $val,
				'inputValue' => $key,
				'name' => CHtml::activeName(self::model(), 'pay_by'),
				'checked' => ($this->pay_by == $key)
			));
		}
		return CJSON::encode($result);
	}

	public function GetExtCarrierData()
	{
		$data = array();
		$carriers = RateCompany::model()->findAll();
		if (count($carriers) != 0)
		{
			foreach ($carriers as $carrier)
			{
				array_push($data, array("id" => "$carrier->id", "code" => "$carrier->code"));
			}
		}
		return CJSON::encode($data);
	}

	public function getAdditionalCharges()
	{
		$data = array();
		$add_costs = ShipmentAdditionalCharge::model()->findAllByAttributes(array('shipment_id' => $this->id));
		if (count($add_costs) != 0)
		{
			foreach ($add_costs as $add_cost)
			{
				array_push($data, array('charge_name' => $add_cost->name, 'charge_cost' => $add_cost->cost));
			}
		}
		return $data;
	}

	public static function bulkOrder($rawdatas, $customer, $contact, $routing_code)
	{
		$failed = array();
		$success = array();
		$line_error = array();
		$counter = 0;
		$column = array();
		foreach ($rawdatas as $data)
		{
			$valid_area = true;
			if ($counter++ == 0)
				continue;
			$column = explode(',', $data);
			$shipment = new Shipment;

			if (count($column) == 21)
			{
				//account detail
				$shipment->setAttribute('customer_id', $customer->id);
				$shipment->setAttribute('origin_code', $routing_code);
				
				//shipper_detail
				$shipment->setAttribute('shipper_name', trim($column[2]));
				$shipment->setAttribute('shipper_company_name', trim($column[1]));
				$shipment->setAttribute('shipper_address', trim($column[3]));
				$shipment->setAttribute('shipper_city', trim($column[5]));
				$shipment->setAttribute('shipper_country', trim($column[6]));
				$shipment->setAttribute('shipper_postal', trim($column[7]));
				$shipment->setAttribute('shipper_phone', trim($column[4]));

				//receiver_detail
				$shipment->setAttribute('receiver_name', trim($column[9]));
				$shipment->setAttribute('receiver_company_name', trim($column[8]));
				$shipment->setAttribute('receiver_address', trim($column[10]));
				$shipment->setAttribute('receiver_city', trim($column[12]));
				$shipment->setAttribute('receiver_country', trim($column[13]));
				$shipment->setAttribute('receiver_postal', trim($column[14]));
				$shipment->setAttribute('receiver_phone', trim($column[11]));

				//shipment_detail
				$shipment->setAttribute('type', 'document');
				$shipment->setAttribute('payer', 'shipper');
				$shipment->setAttribute('payby', 'account');
				$shipment->setAttribute('pieces', trim($column[15]));
				$shipment->setAttribute('package_weight', trim($column[16]));
				$shipment->setAttribute('package_value', trim($column[17]));
				$shipment->setAttribute('service_type', trim($column[18]));
				$shipment->setAttribute('service_id', trim($column[19]));
				$shipment->setAttribute('service_code', trim($column[20]));
				$shipment->setAttribute('destination_code', trim($column[0]));

				$customer_rate = CustomerDiscount::getCustomerDiscountRate($shipment->service_id, $shipment->customer_id);
				if (!(!$customer_rate))
				{
					if ($customer_rate['discount'] == null)
					{
						$customer_rate['discount'] = 0;
					}
					switch ($shipment->service_type)
					{
						case 'City Courier':
							$rate = RateCity::model()->findByAttributes(array('service_id' => $shipment->service_id));
							if (($rate instanceof RateCity))
							{
								if ($customer_rate['harga_invoice'] != 0)
									$price = $customer_rate['harga_invoice'] * RateCity::increment($shipment->package_weight, $rate->weight_inc);
								else
									$price = ($rate->price - ($rate->price * ($customer_rate['discount'] / 100))) * RateCity::increment($shipment->package_weight, $rate->weight_inc);
								$price_vendor = ($rate->price - ($rate->price * ($customer_rate['vendor_discount'] / 100))) * RateCity::increment($shipment->package_weight, $rate->weight_inc);
								;
							}
							else
							{
								$area = Area::getZoneID($shipment->receiver_postal);
								if (!$area)
								{
									$price = 0;
									$price_vendor = 0;
									$valid_area = false;
								}
								else
								{
									$price = RateDomestic::getRatePrice($shipment->service_id, 1, $area['district_id'], $area['zone_id'], $shipment->package_weight, $customer_rate['discount']);
									$price_vendor = RateDomestic::getRatePrice($shipment->service_id, 1, $area['district_id'], $area['zone_id'], $shipment->package_weight, $customer_rate['vendor_discount']);
								}
							}
							break;

						case 'Domestic':
							$area = Area::getZoneID($shipment->receiver_postal);
							if (!$area)
							{
								$price = 0;
								$price_vendor = 0;
								$valid_area = false;
							}
							else
							{
								$price = RateDomestic::getRatePrice($shipment->service_id, 1, $area['district_id'], $area['zone_id'], $shipment->package_weight, $customer_rate['discount']);
								$price_vendor = RateDomestic::getRatePrice($shipment->service_id, 1, $area['district_id'], $area['zone_id'], $shipment->package_weight, $customer_rate['vendor_discount']);
							}
							break;

						case 'International':
							$zone = FALSE;
							if ($shipment->service_code == 'IEX')
								$country = $shipment->receiver_country;
							else if ($shipment->service_code == 'IMX')
								$country = $shipment->shipper_country;
							$zone = ZoneInternational::getZoneCountryData($country);
							if (!(!$zone))
							{
								$price = RateInternational::getRatePrice($shipment->service_id, $shipment->package_weight, $shipment->type, $zone, $customer_rate['discount']);
								$price_vendor = RateInternational::getRatePrice($shipment->service_id, $shipment->package_weight, $shipment->type, $zone, $customer_rate['vendor_discount']);
							}
							else
							{
								$price = 0;
								$price_vendor = 0;
								$valid_area = false;
							}
							break;

						case '':
							continue;
					}

					$shipment->shipping_charges = $price;
					$shipment->vendor_charge = $price_vendor;
					$shipment->awb = '90' . rand(10000000, 99999999);

					while (!$shipment->validate(array('awb')))
					{
						$shipment->awb = '90' . rand(10000000, 99999999);
					}

					if ($shipment->save())
					{
						$additional_costs = ShipmentAdditionalCharge::initSurcharges($shipment);
						$shipment->charges = $shipment->shipping_charges + $additional_costs;
						$shipment->save();
						array_push($success, $counter);
					}
					else
					{
						CVarDumper::dump($shipment->attributes, 10, true);
						CVarDumper::dump($shipment->getErrors(), 10, true);

						array_push($failed, array('counter'=>$counter, 'message'=>$shipment->getErrors()));
					}
				}
				else
				{
					array_push($failed, array('counter'=>$counter, 'message'=>array('this service is not available')));
				}
			}
			else
			{
				array_push($failed, array('counter'=>$counter, 'message'=>array('wrong delimiter format')));
			}
		}
		return array('success' => $success, 'failed' => $failed);
	}
//	public function getSumAttribute($attribute,$booking_code)
//	{
//		$db = Yii::app()->db->createCommand();
//		$db->select('sum('.$attribute.') as sum');
//		$db->from('shipments');
//		$db->where('booking_code=:booking',array(':booking' => $booking_code));
//		$sum = $db->queryScalar();
//		return $sum;
//	}
}