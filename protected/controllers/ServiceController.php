<?php

class ServiceController extends CController
{
	private $token = null;

	public function beforeAction($action)
	{
		$must_no_token_action = array('checkkey', 'resetpassword', 'lostpassword', 'getcourier', 'setdriverlocation', 'getcountry', 'uploadbarang', 'logindriver', 'login', 'register', 'logout', 'checkawb', 'suggestarea', 'suggestpostal', 'suggestdistrict', 'insertlocation');
		$can_no_token_action = array('getRates', 'requestOrder', 'getGroceryRate', 'requestPickUp');
		if (in_array($action->getId(), $must_no_token_action))
		{
			
		}
		else if (in_array($action->getId(), $can_no_token_action))
		{
			if (isset($_REQUEST['key']))
			{
				$key = $_REQUEST['key'];
				$token = Token::model()->findByAttributes(array('token' => $key));
				if (!($token instanceof Token))
				{
					echo CJSON::encode($this->statusError('wrong authentication key'));
					Yii::app()->end();
				}
				$this->token = $token;
				$customer = Customer::model()->findByPk($token->customer_id, 'is_allow_api = 1');
				if (!($customer instanceof Customer))
				{
					echo CJSON::encode($this->statusError('you\'re not permitted to this action'));
					Yii::app()->end();
				}
			}
		}
		else
		{
			if (!isset($_REQUEST['key']))
			{
				echo CJSON::encode($this->statusError('Key not found'));
				Yii::app()->end();
			}
			$key = $_REQUEST['key'];
			if (!isset($key))
			{
				echo CJSON::encode($this->statusError('Please insert token'));
				Yii::app()->end();
			}
			$token = Token::model()->findByAttributes(array('token' => $key));
			if ($token == null)
			{
				echo CJSON::encode($this->statusError('Token Error'));
				Yii::app()->end();
			}
			else
			{
				$this->token = $token;
			}

			$token = Token::model()->findByAttributes(array('token' => $key));
			if (!($token instanceof Token))
			{
				echo CJSON::encode($this->statusError('wrong authentication key'));
				Yii::app()->end();
			}

			$this->token = $token;
			$customer = Customer::model()->findByPk($token->customer_id, 'is_allow_api = 1');
			if (!($customer instanceof Customer))
			{
				echo CJSON::encode($this->statusError('you\'re not permitted to this action'));
				Yii::app()->end();
			}
		}
		return parent::beforeAction($action);
	}

	public function init()
	{
		header('Access-Control-Allow-Origin:*');
		header('Content-type: application/json');
		parent::init();
	}

	public function actions()
	{
		return array(
			'rate' => array(
				'class' => 'CWebServiceAction',
			),
		);
	}
	/*
	 * Check Rate
	 */

	public function actionGetRates()
	{
		$inquiry = new InquiryForm('api-rate');
		$req = Yii::app()->request;
		$inquiryMap = new CMap;
		$inquiryMap->add('shipper_country', $req->getQuery('shipper_country'));
		$inquiryMap->add('shipper_city', $req->getQuery('shipper_city'));
		$inquiryMap->add('shipper_postal', $req->getQuery('shipper_postal'));
		$inquiryMap->add('receiver_country', $req->getQuery('receiver_country'));
		$inquiryMap->add('receiver_city', $req->getQuery('receiver_city'));
		$inquiryMap->add('receiver_postal', $req->getQuery('receiver_postal'));
		$inquiryMap->add('package_weight', $req->getQuery('package_weight'));

		$inquiry->setAttributes($inquiryMap->toArray());
		if (!$inquiry->validate())
		{
			echo CJSON::encode($this->statusError($inquiry->getErrors()));
			Yii::app()->end();
		}

		/**
		 * cek for customer rate management
		 */
		$customer_id = null;
		$allow_api = array();
		if (($this->token instanceof Token))
		{
			$customer_id = $this->token->customer_id;
			$customer_rates = CustomerDiscount::model()->findAllByAttributes(array(
				'customer_id' => $this->token->customer_id,
				'show_in_api' => 1
					));
			foreach ($customer_rates as $cus_rate)
			{
				array_push($allow_api, $cus_rate->service_id);
			}
		}

		/**
		 * for international service
		 */
		if (strtolower($inquiry->receiver_country) != 'indonesia')
		{
			$country = ZoneInternational::model()->findByAttributes(array('country' => strtolower($inquiry->receiver_country)));
			if (!($country instanceof ZoneInternational))
			{
				echo CJSON::encode($this->statusError('There is no available service for the country you\'re requested'));
				Yii::app()->end();
			}

			$rates = RateInternational::getServicesAPI($inquiry->package_weight, $country->zone, $country->transit_time, $customer_id, $allow_api);
			$product = 'International';
			if (count($rates) == 0)
			{
				echo CJSON::encode($this->statusError('No Available Service'));
				Yii::app()->end();
			}

			echo CJSON::encode($this->statusSuccess(array(
						'zone' => $country->zone,
						'service_type' => 'International',
						'rates' => $rates
					)));
			Yii::app()->end();
		}
		/**
		 * domestic and city service
		 */
		else
		{
			$routing = IntraCityRouting::model()->findByAttributes(array('postcode' => $inquiry->receiver_postal));
			if ($routing instanceof IntraCityRouting)
			{
				$area = Area::getZoneID($inquiry->receiver_postal, 'postcode');
				if (!$area)
				{
					echo CJSON::encode($this->statusError('No Available Service'));
					Yii::app()->end();
				}
				$rates1 = RateCity::getCityRateAPI(ProductService::ProductCityCourier, $routing->code, $inquiry->package_weight, $customer_id, $allow_api);
				$rates2 = RateDomestic::getServiceListAPI(1, $area['district_id'], $area['zone_id'], $inquiry->package_weight, ProductService::ProductCityCourier, $customer_id, $allow_api);
				$rates = array_merge($rates1, $rates2);
				$product = 'City Courier';
				if (count($rates) == 0)
				{
					echo CJSON::encode($this->statusError('No Available Service'));
					Yii::app()->end();
				}
			}
			else
			{
				$area = Area::getZoneID($inquiry->receiver_postal, 'postcode');
				if (!$area)
				{
					echo CJSON::encode($this->statusError('No Available Service'));
					Yii::app()->end();
				}

				$rates = RateDomestic::getServiceListAPI(1, $area['district_id'], $area['zone_id'], $inquiry->package_weight, ProductService::ProductDomestic, $customer_id, $allow_api);
				$product = 'Domestic';
				if (count($rates) == 0)
				{
					echo CJSON::encode($this->statusError('No Available Service'));
					Yii::app()->end();
				}
			}
			echo CJSON::encode($this->statusSuccess(array(
						'zone_id' => $area['zone_id'],
						'service_type' => $product,
						'rates' => $rates,
					)));
			Yii::app()->end();
		}
	}

	public function actionGetGroceryRate()
	{
		$inquiry = new InquiryForm('api-rate-grocery');
		$req = Yii::app()->request;
		$inquiryMap = new CMap;

		$inquiryMap->add('receiver_postal', $req->getQuery('receiver_postal'));
		$inquiryMap->add('service_code', $req->getQuery('service_code'));

		$inquiry->setAttributes($inquiryMap->toArray());
		if (!$inquiry->validate())
		{
			echo CJSON::encode($this->statusError($inquiry->getErrors()));
			Yii::app()->end();
		}

		$service_code = ProductService::model()->findByAttributes(array('code' => strtoupper($inquiry->service_code)));
		if (!($service_code instanceof ProductService))
		{
			echo CJSON::encode($this->statusError('No Service Available'));
			Yii::app()->end();
		}
		else if ($service_code->code != 'LSX' && $service_code->code != 'HRX')
		{
			echo CJSON::encode($this->statusError('This service is not available'));
			Yii::app()->end();
		}

		$routing = IntraCityRouting::model()->findByAttributes(array('postcode' => $inquiry->receiver_postal));
		if ($routing instanceof IntraCityRouting)
		{
			$area = Area::getZoneID($inquiry->receiver_postal, 'postcode');
			if (!$area)
			{
				echo CJSON::encode($this->statusError('No Available Service'));
				Yii::app()->end();
			}

			$rates = RateCity::getCityRate(ProductService::ProductCityCourier, $routing->code, 5);
			$rate = array();
			foreach ($rates as $key)
			{
				if ($key['service_code'] == $inquiry->service_code)
				{
					$rate = $key;
				}
			}

			$product = 'City Courier';
			echo CJSON::encode($this->statusSuccess(array(
						'service_type' => $product,
						'rate' => $rate,
					)));
			Yii::app()->end();
		}
		else
		{
			$result = array(
				'status' => 'success',
				'result' => $data,
			);
		}
		echo CJSON::encode($result);
		Yii::app()->end();
	}

	/**
	 *
	 * @param type $propinsi
	 * @param type $kota
	 * @param type $kecamatan
	 * @param type $kodepos
	 * @param type $kelurahan 
	  public function actionListArea($propinsi = '', $kota = '', $kecamatan = '', $kodepos = '', $kelurahan = '') {

	  $criteria=new CDbCriteria;
	  $criteria->select='postcode kodepos, t.name kelurahan, zone.name kecamatan, district.name kota, province.name propinsi';
	  $criteria->join='JOIN zone ON zone.id=zone_id JOIN district ON zone.district_id = district.id JOIN province ON district.province_id = province.id';
	  $criteria->addSearchCondition('postcode', $kodepos.'%', FALSE, 'AND', 'LIKE');
	  $criteria->addSearchCondition('zone.name', $kecamatan, true, 'AND');
	  $criteria->addSearchCondition('district.name', $kota, true, 'AND');
	  $criteria->addSearchCondition('province.name', $propinsi, true, 'AND');
	  $criteria->limit=100;
	  $builder=new CDbCommandBuilder(Yii::app()->db->Schema);
	  $command=$builder->createFindCommand('area', $criteria);
	  $qa=$command->queryAll();
	  if (isset($qa))
	  echo CJSON::encode(array('success' => true, 'result' => $qa));
	  else
	  echo CJSON::encode(array('success' => false, 'result' => 'Input error'));
	  Yii::app()->end();
	  }
	 * */
	public function actionDomesticZone($propinsi = '', $kota = '', $kecamatan = '')
	{

		$criteria = new CDbCriteria;
		$criteria->select = 't.id zone_id, t.name kecamatan, district.name district, province.name propinsi, district.type';
		$criteria->join = 'JOIN district ON t.district_id = district.id JOIN province ON district.province_id = province.id';
		$criteria->addSearchCondition('t.name', $kecamatan, true, 'AND');
		$criteria->addSearchCondition('district.name', $kota, true, 'AND');
		$criteria->addSearchCondition('province.name', $propinsi, true, 'AND');
		$criteria->limit = 100;
		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$command = $builder->createFindCommand('zone', $criteria);
		$qa = $command->queryAll();

		if (isset($qa))
			echo CJSON::encode(array('success' => true, 'result' => $qa));
		else
			echo CJSON::encode(array('success' => false, 'result' => 'Input error'));
		Yii::app()->end();
	}

	public function actionInternationalRate()
	{
		$req = Yii::app()->request;
		$zone = $req->getParam('zone');
		$weight = $req->getParam('weight');
		if (!isset($zone) || !isset($weight))
		{
			echo CJSON::encode($this->statusError('data zone_code atau berat tidak boleh kosong'));
			Yii::app()->end();
		}

		$rates = RateInternational::getServices($weight, $zone, '', TRUE);
		if (count($rates) > 0)
		{
			$result = array(
				'status' => 'success',
				'result' => $rates,
			);
		}
		else
		{
			$result = array(
				'status' => 'error',
				'message' => 'Data tidak berhasil ditemukan karena data pengirim atau penerima tidak terdapat dalam database',
			);
		}

		echo CJSON::encode($result);
		Yii::app()->end();
	}

	public function actionCekAreaPostcode()
	{
		$criteria = new CDbCriteria;
		$criteria->select = 't.name,t.postcode';
		$criteria->join = 'JOIN zone t2 ON t2.id = t.zone_id JOIN district t3 ON t3.id = t2.district_id';
		$criteria->condition = 't3.province_id = 6 AND t2.district_id != 45';

		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$command = $builder->createFindCommand('area', $criteria);
		$data = $command->queryAll();

		echo CJSON::encode($this->statusSuccess($data));
		Yii::app()->end();
	}

	public function actionSuggestarea()
	{
		$name = $_GET['name'];
		$criteria = new CDbCriteria;
		$criteria->select = 't.name,t.postcode';
		$criteria->join = 'JOIN zone t2 ON t2.id = t.zone_id JOIN district t3 ON t3.id = t2.district_id';
		$criteria->condition = 't3.province_id = 6 AND t2.district_id != 45 AND t.name LIKE :sterm';
		$criteria->params = array(":sterm" => "%$name%");

		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$command = $builder->createFindCommand('area', $criteria);
		$data = $command->queryAll();

		echo CJSON::encode($this->statusSuccess($data));
		Yii::app()->end();
	}

	public function actionSuggestdistrict()
	{
		$name = $_GET['name'];
		$criteria = new CDbCriteria;
		$criteria->select = 'name,id';
		$criteria->condition = 'name LIKE :sterm';
		$criteria->params = array(":sterm" => "%$name%");

		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$command = $builder->createFindCommand('district', $criteria);
		$data = $command->queryAll();

		echo CJSON::encode($this->statusSuccess($data));
		Yii::app()->end();
	}

	public function actionSuggestpostal()
	{
		$name = $_GET['name'];
		$district = $_GET['district'];
		$criteria = new CDbCriteria;
		$criteria->select = 't.name,t.postcode';
		$criteria->join = 'JOIN zone t2 ON t2.id = t.zone_id JOIN district t3 ON t3.id = t2.district_id';
		$criteria->condition = 't.name LIKE :sterm AND t3.name LIKE :district';
		$criteria->params = array(":sterm" => "%$name%", ":district" => "%$district%");

		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$command = $builder->createFindCommand('area', $criteria);
		$data = $command->queryAll();

		echo CJSON::encode($this->statusSuccess($data));
		Yii::app()->end();
	}
	/*
	 * Request Order
	 */

	public function actionRequestOrder()
	{
		if (!isset($_POST['Shipment']))
		{
			echo CJSON::encode($this->statusError('Must be in POST method'));
			Yii::app()->end();
		}
		$shipment = new Shipment('api-requestpickup');
		$shipment->attributes = $_POST['Shipment'];
		$shipment->created = time();
		$routing_code = IntraCityRouting::model()->findByAttributes(array('postcode' => $shipment->shipper_postal));
		if ($routing_code instanceof IntraCityRouting)
		{
			$shipment->origin_code = $routing_code->code;
		}
		$price = 0;
		$price_vendor = 0;
		$trans = Yii::app()->db->beginTransaction();
		try
		{
			if (($this->token instanceof Token))
			{
				$customer = Customer::model()->findByPk($this->token->customer_id);
				if (!($customer instanceof Customer))
				{
					throw new ServiceControllerException('You have to login first');
				}
				if (!(!$customer->accountnr))
				{
					$shipment->awb = '70' . rand(10000000, 99999999);
					while (!$shipment->validate())
					{
						$shipment->awb = '70' . rand(10000000, 99999999);
					}
				}
				$shipment->customer_id = $this->token->customer_id;
			}
			else
			{
				$email = '';
				$shipment->setScenario('cekemail');
				if ($shipment->validate())
				{
					if ($shipment->payer == 'shipper' && ($shipment->shipper_email))
						$email = Contact::model()->findByAttributes(array('email' => $shipment->shipper_email));
					elseif ($shipment->payer == 'consignee' && ($shipment->receiver_email))
						$email = Contact::model()->findByAttributes(array('email' => $shipment->receiver_email));
				}
				else
				{
					throw new ServiceControllerException($shipment->getErrors());
				}
				$shipment->setScenario('api-requestpickup');
				if (!($email instanceOf Contact))
				{
					$customer = new Customer;
					if ($shipment->payer == 'shipper')
						$customer->name = $shipment->shipper_name;
					elseif ($shipment->payer == 'consignee')
						$customer->name = $shipment->receiver_name;
					$customer->type = 'personal';
					$customer->accountnr = 'WEB' . time();
					if ($customer->save())
					{
						$contact = new Contact;
						$contact->parent_id = $customer->id;
						$contact->parent_model = 'Customer';
						if ($shipment->payer == 'shipper')
						{
							$contact->full_name = $shipment->shipper_name;
							$contact->address = $shipment->shipper_address;
							$contact->country = $shipment->shipper_country;
							$contact->city = $shipment->shipper_city;
							$contact->postal = $shipment->shipper_postal;
							$contact->email = $shipment->shipper_email;
						}
						elseif ($shipment->payer == 'consignee')
						{
							$contact->full_name = $shipment->receiver_name;
							$contact->address = $shipment->receiver_address;
							$contact->country = $shipment->receiver_country;
							$contact->city = $shipment->receiver_city;
							$contact->postal = $shipment->receiver_postal;
							$contact->email = $shipment->receiver_email;
						}
						if (($contact->save()))
						{
							$shipment->customer_id = $customer->id;
						}
						else
							throw new ServiceControllerException($contact->getErrors());
					}
					else
						throw new ServiceControllerException($customer->getErrors());
				}
				else
				{
					throw new ServiceControllerException('Your email is currently registered as a member, please login to create order');
				}
			}

			if ($shipment->validate())
			{
				$customer_rate = CustomerDiscount::getCustomerDiscountRate($shipment->service_id, $shipment->customer_id);
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
								throw new ServiceControllerException('No services available');
							$price = RateDomestic::getRatePrice($shipment->service_id, 1, $area['district_id'], $area['zone_id'], $shipment->package_weight, $customer_rate['discount']);
							$price_vendor = RateDomestic::getRatePrice($shipment->service_id, 1, $area['district_id'], $area['zone_id'], $shipment->package_weight, $customer_rate['vendor_discount']);
						}
						break;

					case 'Domestic':
						$area = Area::getZoneID($shipment->receiver_postal);
						if (!$area)
							throw new ServiceControllerException('No services available');
						$price = RateDomestic::getRatePrice($shipment->service_id, 1, $area['district_id'], $area['zone_id'], $shipment->package_weight, $customer_rate['discount']);
						$price_vendor = RateDomestic::getRatePrice($shipment->service_id, 1, $area['district_id'], $area['zone_id'], $shipment->package_weight, $customer_rate['vendor_discount']);
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
						break;

					case '':
						continue;
				}

				$shipment->shipping_charges = $price;
				$shipment->vendor_charge = $price_vendor;

				if (!$shipment->save())
					throw new ServiceControllerException($shipment->getErrors());

				$sum_add_cost = ShipmentAdditionalCharge::initSurcharges($shipment);
				/**
				 * temporary disabling additional cost
				 */
				$sum_add_cost = 0;
				$shipment->charges = $shipment->shipping_charges + $sum_add_cost;

				$list_add_cost = $shipment->getAdditionalCharges();

				if (!$shipment->save())
					throw new ServiceControllerException($shipment->getErrors());
			}
			else
			{
				throw new ServiceControllerException($shipment->getErrors());
			}

			if (isset($_GET['confirm']) && $_GET['confirm'] == 1)
			{
				$trans->commit();
				$data = array(
					'shipment_id' => $shipment->id,
					'status' => $shipment->shipping_status,
					'time' => date('Y-m-d H:i:s', $shipment->created),
					'charges' => $shipment->charges,
					'awb' => $shipment->awb
				);
			}
			elseif ((isset($_GET['confirm']) && $_GET['confirm'] == 0) || !isset($_GET['confirm']))
			{
				$data = array(
					'confirm' => 0,
					'additional_cost' => $list_add_cost,
					'shipping_charges' => $shipment->shipping_charges,
					'total' => $shipment->charges,
					'shipper_name' => $shipment->shipper_name,
					'shipper_address' => $shipment->shipper_address,
					'shipper_city' => $shipment->shipper_city,
					'shipper_postal' => $shipment->shipper_postal,
					'shipper_country' => $shipment->shipper_country,
					'receiver_name' => $shipment->receiver_name,
					'receiver_address' => $shipment->receiver_address,
					'receiver_city' => $shipment->receiver_city,
					'receiver_postal' => $shipment->receiver_postal,
					'receiver_country' => $shipment->receiver_country,
					'goods_desc' => $shipment->goods_desc,
					'shipment_value' => $shipment->package_value,
					'weight' => $shipment->package_weight,
					'pieces' => $shipment->pieces,
					'pay_bay' => $shipment->pay_by,
					'payer' => $shipment->payer,
					'customer_id' => $customer->id
				);
				$trans->rollback();
			}
		}
		catch (ServiceControllerException $e)
		{
			$errors = $e->errors;

			$trans->rollback();
			echo CJSON::encode($this->statusError($errors));
			Yii::app()->end();
		}
		catch (CDbException $e)
		{
			$trans->rollback();
			echo CJSON::encode($this->statusError($e));
			Yii::app()->end();
		}

		echo CJSON::encode($this->statusSuccess($data));
		Yii::app()->end();
	}

	public function actionRequestPickUp()
	{
		$req = Yii::app()->request;
		$shipment_id = $req->getQuery('shipment_id');

		if (!isset($_POST['Booking']))
		{
			echo CJSON::encode($this->statusError('Must be in POST method'));
			Yii::app()->end();
		}
		$booking = new Booking;
		$booking->setAttributes($_POST['Booking']);
		$booking->setAttribute('booking_code', dechex(time()));

		if ($shipment_id != null)
		{
			$shipment = Shipment::model()->findByPk($shipment_id);
			if (!($shipment instanceof Shipment))
			{
				echo CJSON::encode($this->statusError('Your Shipment is invalid'));
				Yii::app()->end();
			}
		}

		$trans = Yii::app()->db->beginTransaction();
		try
		{
			if ($booking->save())
			{
				if (isset($shipment) && ($shipment instanceof Shipment))
				{
					Booking::model()->updateByPk($booking->id, array('customer_id' => $shipment->customer_id));
					$shipment->setAttribute('booking_id', $booking->id);
					if (!$shipment->save())
						throw ServiceControllerException($shipment->getErrors());
				}
				else
				{
					if (($this->token instanceof Token))
					{
						$customer = Customer::model()->findByPk($this->token->customer_id);
						if (!($customer instanceof Customer))
						{
							throw new ServiceControllerException('You have to login first');
						}
						else
						{
							Booking::model()->updateByPk($booking->id, array('customer_id' => $customer->id));
						}
					}
					else
					{
						$customer = new Customer;
						$customer->name = $booking->name;
						$customer->type = 'personal';
						$customer->accountnr = 'WEB' . time();
						if ($customer->save())
						{
							$contact = new Contact;
							$contact->parent_id = $customer->id;
							$contact->parent_model = 'Customer';

							$contact->full_name = $booking->name;
							$contact->address = $booking->address;
							$contact->country = $booking->country;
							$contact->city = $booking->city;
							$contact->postal = $booking->postal;

							if (($contact->save()))
							{
								Booking::model()->updateByPk($booking->id, array('customer_id' => $customer->id));
							}
							else
								throw new ServiceControllerException($contact->getErrors());
						}
						else
							throw new ServiceControllerException($customer->getErrors());
					}
				}
				$data = array('booking_code' => $booking->booking_code);
			}
			else
			{
				throw ServiceControllerException($booking->getErrors());
			}
			$trans->commit();
		}
		catch (ServiceControllerException $e)
		{
			$errors = $e->errors;

			$trans->rollback();
			echo CJSON::encode($this->statusError($errors));
			Yii::app()->end();
		}
		echo CJSON::encode($this->statusSuccess($data));
		Yii::app()->end();
	}
	/*
	 * Order Tracking
	 */

	public function actionCheckawb()
	{
		if (!isset($_GET['awb']))
		{
			echo CJSON::encode($this->statusError('AWB Number required'));
			Yii::app()->end();
		}

		$shipment = Shipment::model()->findByAttributes(array('awb' => $_GET['awb']));
		if (!($shipment instanceof Shipment))
		{
			echo CJSON::encode($this->statusError('AWB Number not found'));
			Yii::app()->end();
		}

		$criteria = new CDbCriteria;
		$criteria->select = 't2.status,remark,description,event_time';
		$criteria->join = 'JOIN shipment_status t2 ON t2.id = t.status JOIN users t3 ON t3.id = t.user_id';
		$criteria->params = array(
			':shipment_id' => $shipment->id
		);
		$criteria->condition = 't.shipment_id =:shipment_id AND t.with_mde = 0';
		$criteria->order = 't.id DESC';

		$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
		$command = $builder->createFindCommand('shipment_event', $criteria);
		$tracking = $command->queryAll();

		echo CJSON::encode($this->statusSuccess($tracking));
		Yii::app()->end();
	}

	public function statusError($err)
	{
		return array(
			'status' => 'error',
			'message' => $err,
		);
	}

	public function statusSuccess($data)
	{
		return array(
			'status' => 'success',
			'data' => $data,
		);
	}
	/*
	 * @method : Register 
	 * @request : 
	 * @response : 
	 */

	public function actionRegister()
	{
		if (!isset($_POST['contact']))
		{
			echo CJSON::encode($this->statusError('form not available'));
			Yii::app()->end();
		}
		$checkUsername = Customer::model()->findByAttributes(array('username' => $_POST['contact']['email']));
		$checkEmail = Contact::model()->findByAttributes(array('email' => $_POST['contact']['email']));
		if ($checkUsername !== null)
		{
			echo CJSON::encode($this->statusError(array('field' => 'username', 'message' => 'username already exist')));
			Yii::app()->end();
		}
		else if ($checkEmail !== null)
		{
			echo CJSON::encode($this->statusError(array('field' => 'email', 'message' => 'email already exist')));
			Yii::app()->end();
		}

		$customer = new Customer('api');
		$customer->created = time();
		$customer->name = $_POST['contact']['name'];
		$customer->type = $_POST['contact']['type'];
		$customer->username = $_POST['contact']['email'];
		$customer->password = Yii::app()->hasher->hashPassword($_POST['contact']['password']);
		$customer->accountnr = 'REG' . time();
		$customer->save(false);

		$contact = new Contact;
		$contact->attributes = $_POST['contact'];
		$contact->parent_id = $customer->id;
		$contact->parent_model = 'Customer';
		if ($contact->save())
		{
			echo CJSON::encode($this->statusSuccess(array('customer' => $customer, 'contact' => $contact)));
		}
		else
		{
			var_dump($contact->getErrors());
			exit;
		}
	}

	public function actionLogin()
	{
		if (!isset($_REQUEST['LoginForm']))
		{
			echo CJSON::encode($this->statusError('form not available'));
			Yii::app()->end();
		}
		$customer = Customer::model()->findByAttributes(array('username' => $_REQUEST['LoginForm']['username']));
		if ($customer == null)
			echo CJSON::encode($this->statusError('username error'));
		elseif (!$customer->validatePassword($_REQUEST['LoginForm']['password']))
			echo CJSON::encode($this->statusError('password error'));
		else
			echo CJSON::encode($this->statusSuccess(array(
						'access_token' => Token::create($customer->id),
						'username' => $customer->username,
						'id' => $customer->id
					)));
		Yii::app()->end();
	}

	public function actionLogout()
	{
		$token = $_POST['token'];
		if (Token::deleteByToken($token))
			echo CJSON::encode($this->statusSuccess($token));
		echo CJSON::encode($this->statusError(0));
	}

	public function actionGetcustomer()
	{
		$id = $_REQUEST['id'];
		$cusromer = Customer::model()->findByPk($id);
		$contact = Contact::model()->findByAttributes(array('parent_id' => $id, 'parent_model' => 'Customer'));
		echo CJSON::encode($this->statusSuccess(array('contact' => $contact, 'customer' => $cusromer)));
	}

	public function actionEditcontact()
	{
		$id = $_POST['id'];
		$contact = Contact::model()->findByAttributes(array('parent_id' => $id, 'parent_model' => 'Customer'));
		$contact->attributes = $_POST['contact'];
		if ($contact->save())
		{
			echo CJSON::encode($this->statusSuccess(array('contact' => $contact)));
		}
	}

	public function actionChangepassword()
	{
		$id = $_POST['UserForm']['id'];
		$password = $_POST['UserForm']['password'];
		$customer = Customer::model()->findByPk($id);
		if ($customer == null)
			throw new CHttpException(404, 'Customer null');
		$customer->password = Yii::app()->hasher->hashPassword($password);
		if ($customer->update())
			echo CJSON::encode($this->statusSuccess(1));
		else
			echo CJSON::encode($this->statusError($_POST));
	}

	public function actionLostpassword()
	{
		$contact = Contact::model()->findByAttributes(array('email' => $_POST['email'], 'parent_model' => 'Customer'));
		if ($contact == null && $contact == '')
		{
			echo CJSON::encode($this->statusError('Email not valid'));
			Yii::app()->end();
		}
		$customer = Customer::model()->findByPk($contact->parent_id);
		$customer->key = Yii::app()->hasher->hashPassword(time() . $contact->email);
		if ($customer->update())
			echo CJSON::encode($this->statusSuccess($customer));
	}

	public function actionResetpassword($key)
	{
		$customer = Customer::model()->findByAttributes(array('key' => $key));
		if ($customer == null)
			echo CJSON::encode($this->statusError('Key Expired'));
		else
		{
			$customer->password = Yii::app()->hasher->hashPassword($_POST['password']);
			$customer->key = NULL;
			if ($customer->update())
				echo CJSON::encode($this->statusSuccess(1));
		}
	}

	public function actionCheckkey($key)
	{
		$customer = Customer::model()->findByAttributes(array('key' => $key));
//		echo CJSON::encode($this->statusError($customer));
//		exit;
		if ($customer == null)
			echo CJSON::encode($this->statusError('Key Expired'));
		else
			echo CJSON::encode($this->statusSuccess($customer));
	}

	public function actionSetdriverlocation()
	{
		if (!isset($_REQUEST['user_id']) || !isset($_REQUEST['lat']) || !isset($_REQUEST['long']))
		{
			echo CJSON::encode($this->statusError('Check your parameters'));
			Yii::app()->end();
		}
		$driver = Driver::model()->findByPk($_REQUEST['user_id']);
		if ($driver == null)
			echo CJSON::encode($this->statusError('User not found'));
		$driverLocation = new DriverLocation;
		$driverLocation->driver_user_id = $_REQUEST['user_id'];
		$driverLocation->time = time();
		$driverLocation->lat = $_REQUEST['lat'];
		;
		$driverLocation->long = $_REQUEST['long'];
		if ($driverLocation->save())
			echo '1';
	}

	public function actionGetcourier()
	{
		$connection = Yii::app()->db;
		$sql = 'select * from (select * from driver_location ORDER BY id DESC) AS x GROUP BY driver_user_id';
//		$sql = 'select * from (select * from driver_location where driver_user_id=1 ORDER BY id DESC) AS x GROUP BY driver_user_id';
		$drivers = $connection->createCommand($sql)->queryAll();
		$data = array();
		$mapMethod = Yii::app()->user->getState('map_method');
		$mapUserId = Yii::app()->user->getState('map_user_id');
		foreach ($drivers as $driver)
		{
			$user_id = $driver['driver_user_id'];
			if ($mapMethod == 'one')
			{
				if ($user_id == $mapUserId)
				{
					$data[$user_id] = array(
						'name' => User::getUsername($driver['driver_user_id']),
						'lat' => $driver['lat'],
						'long' => $driver['long'],
						'time' => $driver['time'],
						'counter' => 1,
						'poto' => User::getAvatar($driver['driver_user_id'])
					);
				}
				else
				{
					$data[$user_id] = array();
				}
			}
			else
			{
				$data[$user_id] = array(
					'name' => User::getUsername($driver['driver_user_id']),
					'lat' => $driver['lat'],
					'long' => $driver['long'],
					'time' => $driver['time'],
					'counter' => 1,
					'poto' => User::getAvatar($driver['driver_user_id'])
				);
			}
		}
		echo json_encode($data);
		Yii::app()->end();
	}
	/*
	 * api Driver
	 */

	public function actionInsertlocation()
	{
		session_start();
		$connection = Yii::app()->db;
		if (!isset($_SESSION['test_id']))
		{
			$_SESSION['test_id'] = 1;
			$sql = 'update demo_location set status=0';
			$connection->createCommand($sql)->execute();
//			$sql = 'DELETE FROM driver_location WHERE id > 7';
//			$connection->createCommand($sql)->execute();
//			$sql = 'ALTER TABLE driver_location AUTO_INCREMENT = 1';
//			$connection->createCommand($sql)->execute();
		}
		$driver_user_id = array('6', '7', '8', '9', '10');
		$randomuser = array_rand($driver_user_id, 2);

		for ($counter = 0; $counter < 2; $counter++)
		{
			$id = $driver_user_id[$randomuser[$counter]];
			$sql = 'SELECT * FROM demo_location WHERE user_id =' . $id . ' AND status = 0 limit 0,1';
			$demo = $connection->createCommand($sql)->queryRow();

			$sql = 'INSERT INTO driver_location' .
					'(driver_location.driver_user_id,driver_location.time,driver_location.lat,driver_location.long)' .
					'VALUES(' . $demo['user_id'] . ',' . time() . ',' . $demo['lat'] . ',' . $demo['long'] . ')';
			$connection->createCommand($sql)->execute();
			$sql = 'UPDATE demo_location SET status=1 WHERE id=' . $demo['id'];
			$connection->createCommand($sql)->execute();
//			echo $sql;
//			echo 'User ID = '.$id.' '.$demo['lat'].'<br>';
			//echo $driver_user_id[$randomuser[$counter]].'<br>';
		}
//		if($_SESSION['test_id']<6){
//			$id = $_SESSION['test_id'];
//			$sql_demo = 'select * from demo_location where id = '.$id;
//			$demos = $connection->createCommand($sql_demo)->queryRow();
//			$sql = "INSERT INTO driver_location (driver_location.driver_user_id,driver_location.time,driver_location.lat,driver_location.long)
//							VALUES(".$demos['user_id'].",".time().",".$demos['lat'].",".$demos['long'].")";
//			echo $sql;
//			$connection->createCommand($sql)->execute();
//		}

		if ($_SESSION['test_id'] == 5)
			session_destroy();
	}

	public function actionLogindriver()
	{
		if (!isset($_REQUEST['username']))
		{
			echo CJSON::encode($this->statusError('form not available'));
			Yii::app()->end();
		}
		$user = User::model()->findByAttributes(array('username' => $_REQUEST['username']));
		if ($user == null)
			echo CJSON::encode($this->statusError('Username null'));
		$driver = Driver::model()->findByPk($user->id);
		if ($driver == null)
			echo CJSON::encode($this->statusError('User not assign Driver'));
		if (!$user->validatePassword($_REQUEST['password']))
			echo CJSON::encode($this->statusError('Username or password is wrong'));
		else
		{

			echo CJSON::encode($this->statusSuccess(array(
						'access_token' => Driver::generateToken($user->id)
					)));
		}
		Yii::app()->end();
	}

	public function actionUploadbarang()
	{
		$pathRoot = Yii::app()->basePath;
		echo CJSON::encode($this->statusSuccess(array('token' => $_REQUEST['token'], 'image' => $_REQUEST['image'])));
		$driver = Driver::model()->findByAttributes(array('token' => $_REQUEST['token']));
		$potoBarang = new PotoBarang();
		$potoBarang->time = time();
		$potoBarang->users_id = $driver->user_id;
		$potoBarang->image = $_REQUEST['image'];
		$potoBarang->Save(false);
	}

	public function actionGetcountry()
	{
		$connection = Yii::app()->db;
		$sql = 'select * from zone_international';
		$countries = $connection->createCommand($sql)->queryAll();
		$contry = array();
		$contry['Indonesia'] = 'Indonesia';
		foreach ($countries as $countrie)
		{
			$contry[$countrie['country']] = $countrie['country'];
		}
		echo CJSON::encode($this->statusSuccess($contry));
		Yii::app()->end();
	}

	public function actionGetExtRoutingCode()
	{
		$result = array('status' => 'error', 'data' => '');
		$data = array();
		if (isset($_GET['country']) && isset($_GET['postal']))
		{
			$country = ucfirst($_GET['country']);
			$postal = $_GET['postal'];
			if ($country != 'Indonesia')
			{
				$routings = RateCompany::model()->findAllByAttributes(array('is_international' => 1));
				if (count($routings) != 0)
				{
					foreach ($routings as $routing)
						array_push($data, array('code' => $routing->code));
					$result = array('status' => 'success', 'data' => $data, 'service_type' => 'International');
				}
			}
			else
			{
				$routing = IntraCityRouting::model()->findByAttributes(array('postcode' => $postal));
				if (($routing instanceof IntraCityRouting))
				{
					array_push($data, array('code' => $routing->code));
					$result = array('status' => 'success', 'data' => $data, 'service_type' => 'City Courier');
				}
				else
				{
					$area = Area::getZoneID($postal);
					if (!(!$area))
					{
						$routings = RateCompany::model()->findAllByAttributes(array('is_domestic' => 1));
						if (count($routings) != 0)
						{
							foreach ($routings as $routing)
								array_push($data, array('code' => $routing->code));
							$result = array('status' => 'success', 'data' => $data, 'service_type' => 'Domestic');
						}
					}
				}
			}

			echo CJSON::encode($result);
			Yii::app()->end();
		}
	}

	public function actionGetExtTypeService()
	{
		$result = array('status' => 'error', 'data' => '');
		$data = array();
		if (isset($_REQUEST['Shipment']))
		{
			$shipment = new Shipment;
			$shipment->setAttributes($_REQUEST['Shipment']);
			if ($shipment->customer_id != '')
			{
				$join = 'INNER JOIN product_service t2 ON t.id = t2.product_id INNER JOIN service_detail t3 ON t2.id = t3.product_service_id INNER JOIN rate_company_service t4 ON t4.id = t3.rate_company_service_id JOIN rate_company t5 ON t5.id = t4.rate_company_id JOIN customer_discount t6 ON t3.id = t6.service_id';
				$select = 't4.id as service_id,t2.name as service_name,t2.code,t4.name as carrier_service,t5.name vendor_name,t6.use_rate';
			}
			else
			{
				$join = 'INNER JOIN product_service t2 ON t.id = t2.product_id INNER JOIN service_detail t3 ON t2.id = t3.product_service_id INNER JOIN rate_company_service t4 ON t4.id = t3.rate_company_service_id JOIN rate_company t5 ON t5.id = t4.rate_company_id';
				$select = 't4.id as service_id,t2.name as service_name,t2.code,t4.name as carrier_service,t5.name vendor_name';
			}
			$cek_routing = RateCompany::model()->findByAttributes(array('code' => $shipment->destination_code));

			$criteria = new CDbCriteria;
			$criteria->join = $join;
			$criteria->select = $select;
			$criteria->addSearchCondition('t.name', $shipment->service_type);
			if ($shipment->customer_id != '')
			{
				$criteria->params[':customer_id'] = $shipment->customer_id;
				$criteria->addCondition('t6.customer_id =:customer_id AND use_rate = 1');
			}

			if ($shipment->service_type == 'City Courier')
			{
				if (($cek_routing instanceof RateCompany))
					$criteria->addSearchCondition('t5.code', $shipment->destination_code);
				else
					$criteria->addSearchCondition('t5.id', 5);
			}

			if ($shipment->service_type == 'Domestic')
				$criteria->addSearchCondition('t5.code', $shipment->destination_code);

			if ($shipment->service_type == 'International')
			{
				$criteria->addSearchCondition('t5.code', $shipment->destination_code);
				if (ucfirst($shipment->receiver_country) != 'indonesia')
					$criteria->addSearchCondition('t2.id', 10);
				else
					$criteria->addSearchCondition('t2.id', 11);
			}

			$builder = new CDbCommandBuilder(Yii::app()->db->Schema);
			$command = $builder->createFindCommand('product', $criteria);
			$services = $command->queryAll();

			if (!(!$services))
			{
				$result = array('status' => 'success', 'data' => $services);
			}
		}
		echo CJSON::encode($result);
		Yii::app()->end();
	}

	public function actionBulkentry()
	{
		if (!($this->token instanceof Token))
		{
			echo CJSON::encode($this->statusError('You have to login first'));
			Yii::app()->end();
		}

		ini_set('max_execution_time', 300);
		$model = new FOrderDataEntry;

		if (isset($_FILES['FOrderDataEntry']))
		{
			$model->attributes = $_FILES['FOrderDataEntry'];
			$model->setScenario('api');
			if ($model->validate(array('file')))
			{
				$model->setScenario('insert');
				$customer = Customer::model()->findByPk($this->token->customer_id);
				$contact = $customer->getContactData();

				$csvFile = CUploadedFile::getInstance($model, 'file');
				$tempLoc = $csvFile->getTempName();
				$rawdatas = file($tempLoc);

				$city_routing = IntraCityRouting::model()->findByAttributes(array('postcode' => $contact->postal));
				if ($city_routing instanceof IntraCityRouting)
					$routing_code = $city_routing->code;
				else
					$routing_code = '';

				try
				{
					$trans = Yii::app()->db->beginTransaction();
					$bulk = Shipment::bulkOrder($rawdatas, $customer, $contact, $routing_code);

					$list_failed = implode(', ', $bulk['failed']);
					$list_success = implode(', ', $bulk['success']);

					echo CJSON::encode($this->statusSuccess(array(
								'failed' => $list_failed,
								'success' => $list_success
							)));
					Yii::app()->end();
					$trans->commit();
				}
				catch (ServiceControllerException $e) // an exception is raised if a query fails
				{
					CVarDumper::dump($e, 10, TRUE);
					exit;
					$trans->rollBack();
				}
			}
			else
			{
				echo CJSON::encode($this->statusError($model->getErrors()));
				Yii::app()->end();
			}
		}
		else
		{
			echo CJSON::encode($this->statusError('The File is not exixst'));
			yii::app()->end();
		}
	}
}

class ServiceControllerException extends CException
{
	public $errors;

	public function __construct($errors)
	{
		$this->errors = $errors;
	}
}