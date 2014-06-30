<?php

class ShipmentController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	public function actionSuggest($from, $select)
	{
		Yii::import('ext.CSuggest');
		if (isset($_GET['term']) && Yii::app()->request->isAjaxRequest)
		{
			$where = array();

			if (isset($_GET['where']))
				$where = $_GET['where'];
			$arrayselect = CSuggest::renderCallBack($from, $select, $where);
		}
	}

	public function actionCheckawb()
	{
		if (isset($_GET['awb']))
		{
			
		}
		$this->render('checkawb');
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function beforeCreate($model)
	{
		$this->layout = '//layouts/column1';
		if (isset($_POST['Shipment']))
		{
			$model->attributes = $_POST['Shipment'];
			if (!$model->service_type)
				$model->validate();
			else
				$this->redirect(array('create', 'service_type' => $model->service_type));
		}
		$this->render('beforeCreate', array(
			'model' => $model
		));
	}

	public function actionCreateInquiry($cid)
	{
		$shipment = new Shipment;
		$inquiry = new InquiryForm;
		Yii::app()->user->setState('Shipment-items', '');
		Yii::app()->user->setState('Shipment-add_costs', '');
		Yii::app()->user->setState('Shipment', '');

		$items[] = new ShipmentItem;
		$costs[] = new ShipmentAdditionalCharge;

		$customer = Customer::model()->findByPk($cid);
		$contact = Contact::model()->find('parent_model=:pm and parent_id=:pid', array(':pm' => 'Customer', ':pid' => $customer->id));

		$good_types = GoodType::model()->findAll();
		$shipment->type = 'document';
		$inquiry->pickup_date = date('m/d/Y', time());

		if (isset($_POST['InquiryForm']) && isset($_POST['Shipment']))
		{
			$shipment->setScenario('insert');
			$inquiry->setAttributes($_POST['InquiryForm']);
			if ($inquiry->validate())
				$shipment->setAttributes($inquiry->attributes);
			$shipment->setAttributes($_POST['Shipment']);

			/*
			 * shipment items
			 */
			if ($shipment->validate())
			{
				$shipment_items = $_POST['ShipmentItem'];
				$items = array();
				$weight_to_count = array();
				foreach ($shipment_items as $item)
				{
					$shipment_item = new ShipmentItem();
					$shipment_item->setScenario('inquiry');

					$shipment_item->attributes = $item;
					if ($shipment_item->validate())
					{
						$items[] = $shipment_item;
						Yii::app()->user->setState('Shipment-items', $items);
						array_push($weight_to_count, $shipment_item->getWeightToCount());
					}
				}
				$shipment->package_weight = array_sum($weight_to_count);

				/*
				 * shipment additional costs
				 */
				$shipment_costs = $_POST['ShipmentAdditionalCharge'];
				$costs = array();
				$arr_costs = array();

				foreach ($shipment_costs as $cost)
				{
					$shipment_costs = new ShipmentAdditionalCharge();
					$shipment_costs->setScenario('inquiry');

					$shipment_costs->attributes = $cost;
					if ($shipment_costs->validate())
					{
						$costs[] = $shipment_costs;
						Yii::app()->user->setState('Shipment-add_costs', $costs);
						array_push($arr_costs, $shipment_costs->cost);
					}
				}
				$total_add_cost = array_sum($arr_costs);
				$shipment->charges = $shipment->shipping_charges + $total_add_cost + $shipment->cod;
				Yii::app()->user->setState('Shipment', $shipment);
				$this->redirect(array('view'));
			}
		}
		$shipment->pickup_address = $contact->address;
		$shipment->payer = 'shipper';
		$shipment->pay_by = 'account';
		$inquiry->shipper_city = 'Jakarta';
		$inquiry->shipper_country = 'Indonesia';

		$data_render = array(
			'inquiry' => $inquiry,
			'customer' => $customer,
			'good_types' => $good_types,
			'items' => $items,
			'costs' => $costs,
			'contact' => $contact,
			'shipment' => $shipment,
		);

		$this->render('createInquiry', $data_render);
	}

	public function actionCreate($service_type = '')
	{
		$items = array();
		$costs = array();
		$model = new Shipment;

		if ($service_type == '')
			$this->beforeCreate($model);
		else
		{
			if (!array_key_exists($service_type, $model->Service_type))
				throw new CHttpException(404, 'Halaman tidak ditemukan');
			$model->service_type = $service_type;
			$this->performAjaxValidation($model);
			$good_types = GoodType::model()->findAll();
			$weight_to_count = array();
			$arr_cost = array();
			$total_cost = 0;
			$total_weight = 0;
			$ratePrice = 0;

			/**
			 * model untuk data tambahan  
			 */
			if ($model->service_type == 'domestic')
				$model_domestic = new ShipmentDomestic;
			elseif ($model->service_type == 'city')
				$model_city = new ShipmentIntracity;
			elseif ($model->service_type == 'international')
				$model_international = new ShipmentInternational;

			if (isset($_POST['Shipment']))
			{
				$model->setAttributes($_POST['Shipment']);

				/**
				 * set the attributs to each service type 
				 */
				if ($model->service_type == 'domestic' && isset($_POST['ShipmentDomestic']))
					$model_domestic->setAttributes($_POST['ShipmentDomestic']);
				elseif ($model->service_type == 'city' && isset($_POST['ShipmentIntracity']))
					$model_city->setAttributes($_POST['ShipmentIntracity']);
				elseif ($model->service_type == 'international' && isset($_POST['ShipmentInternational']))
					$model_international->setAttributes($_POST['ShipmentInternational']);

				$trans = Yii::app()->db->beginTransaction();
				try
				{
					if ($model->save())
					{
						/**
						 * this is for adding shipment events
						 */
						$shipment_event = new ShipmentEvent();
						$shipment_event->shipment_id = $model->id;
						$shipment_event->status = $model->shipping_status;
						$shipment_event->user_id = Yii::app()->user->id;
						$shipment_event->save();

						/**
						 * this is for shipment items 
						 */
						$shipment_items = $_POST['ShipmentItem'];
						foreach ($shipment_items as $item)
						{
							$shipment_item = new ShipmentItem();
							$shipment_item->attributes = $item;
							$shipment_item->shipment_id = $model->id;
							$shipment_item->save();
							array_push($weight_to_count, $shipment_item->getWeightToCount());
						}
						$total_weight = array_sum($weight_to_count);

						/**
						 * this is for shipment additional cost 
						 */
						$shipment_costs = $_POST['ShipmentAdditionalCharge'];
						foreach ($shipment_costs as $cost)
						{
							$shipment_cost = new ShipmentAdditionalCharge();
							$shipment_cost->attributes = $cost;
							$shipment_cost->shipment_id = $model->id;
							$shipment_cost->save();
							array_push($arr_cost, $shipment_cost->cost);
						}
						$total_cost = array_sum($arr_cost);

						/**
						 * Get the rate price base on service_type 
						 */
						if ($model->service_type == 'domestic')
						{
							$ratePrice = RateDomestic::getRatePrice($model->service_id, 1, $model_domestic->district_id, $model_domestic->zone_id, $total_weight);
							$model_domestic->shipment_id = $model->id;
							$model_domestic->save();
						}
						elseif ($model->service_type == 'city')
						{
							$ratePrice = IntraCityServices::getRates($model->service_id, $area_id = $model_city->area_id, $total_weight);
							$model_city->shipment_id = $model->id;
							$model_city->save();
						}
						elseif ($model->service_type == 'international' && isset($_POST['ShipmentInternational']))
						{
							$ratePrice = RateInternational::getRatePrice($model->service_id, $total_weight, $model->type, $model_international->zone);
							$model_international->shipment_id = $model->id;
							$model_international->save();
						}

						$total_cost = $total_cost + $ratePrice;

						$model->package_weight = $total_weight;
						$model->charges = $total_cost;
						$model->save();
						$trans->commit();
						$this->redirect(array('admin'));
					}
				}
				catch (CDbException $e)
				{
					$trans->rollback();
					throw $e;
				}
			}

			$items[] = new ShipmentItem;
			$costs[] = new ShipmentAdditionalCharge;

			$data_render = array(
				'model' => $model,
				'service_type' => $service_type,
				'items' => $items,
				'costs' => $costs,
				'good_types' => $good_types,
			);

			if ($model->service_type == 'domestic')
				$data_render['model_domestic'] = $model_domestic;
			else if ($model->service_type == 'city')
				$data_render['model_city'] = $model_city;
			else if ($model->service_type == 'international')
				$data_render['model_international'] = $model_international;

			$this->render('create', $data_render);
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		if (isset($_POST['Shipment']))
		{
			$shipment = $this->loadModel($id);
			$shipment->setAttributes($_POST['Shipment']);

			$trans = Yii::app()->db->beginTransaction();
			try
			{
				if ($shipment->save())
				{
					Yii::app()->user->setFlash('success', 'Order Data has successfully updated');
					$trans->commit();
					$this->redirect(array('ordertracking/trackingDetails', 'id' => $id));
				}
				else
					throw new CException(var_export($shipment->getErrors()));
			}
			catch (CException $e)
			{
				$trans->rollback();
				throw $e;
			}
		}
	}

	/**
	 * to render partial items
	 * @param type $index 
	 */
	public function actionItem($index)
	{
		$item = new ShipmentItem;
		$this->renderPartial('_item', array(
			'item' => $item,
			'index' => $index,
		));
	}

	/**
	 * to render partial additional costs
	 * @param type $index 
	 */
	public function actionCost($index)
	{
		$cost = new ShipmentAdditionalCharge;
		$this->renderPartial('_cost', array(
			'cost' => $cost,
			'index' => $index,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if (Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
	}

	/**
	 *  for Customer Service View
	 */
	public function actionCustomerService()
	{
		$this->layout = '//layouts/column1';
		$model = new Shipment('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['Shipment']))
			$model->attributes = $_GET['Shipment'];

		$this->render('admin', array(
			'model' => $model,
		));
	}

	/**
	 *  for Operation View
	 */
	public function actionOperation($booking_id)
	{
		$this->layout = '//layouts/column1';
		$model = new Shipment('search');

		$model->booking_id = $booking_id;
		$this->render('admin_op', array(
			'model' => $model,
		));
	}

	public function actionEntryBulkOrder()
	{
		ini_set('max_execution_time', 300);
		$this->layout = '//layouts/column2';
		$model = new FOrderDataEntry;

		if (isset($_POST['FOrderDataEntry']))
		{
			$model->attributes = $_POST['FOrderDataEntry'];
			if ($model->validate())
			{
				$customer = Customer::model()->findByAttributes(array('accountnr' => $model->customer_account));
				$contact = $customer->getContactData();

				$csvFile = CUploadedFile::getInstance($model, 'file');
				$tempLoc = $csvFile->getTempName();
				$rawdatas = file_get_contents($tempLoc);
				$rawdatas = trim($rawdatas);
				$data = explode("\r", $rawdatas);
				$data = count($data) != 1 ? $data : explode("\n", $rawdatas);

				$city_routing = IntraCityRouting::model()->findByAttributes(array('postcode' => $contact->postal));
				if ($city_routing instanceof IntraCityRouting)
					$routing_code = $city_routing->code;
				else
					$routing_code = '';
				try
				{
					$trans = Yii::app()->db->beginTransaction();
					$bulk = Shipment::bulkOrder($data, $customer, $contact, $routing_code);
					
					if (count($bulk['failed']) != 0)
					{
						$counter = array();
						foreach ($bulk['failed'] as $key =>$val)
						{
							array_push($counter, $val['counter']);
						}

						$list_failed = implode(', ', $counter);
						Yii::app()->user->setFlash('error', 'Failed at line : ' . $list_failed);
					}
					else
					{
						Yii::app()->user->setFlash('success', 'Success importing all datas');
					}
					$trans->commit();
				}
				catch (Exception $e) // an exception is raised if a query fails
				{
					CVarDumper::dump($e, 10, TRUE);
					exit;
					$trans->rollBack();
				}
			}
		}
		$this->render("importDataEntry", array('model' => $model));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = Shipment::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'shipment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionSuggestCustomer()
	{
		if (Yii::app()->request->isAjaxRequest && isset($_GET['term']) && ($keyword = trim($_GET['term'])) !== '')
		{
			$customers = Customer::model()->suggest($keyword, $_GET['to_search']);
			$this->setJsonHeader();
			echo CJSON::encode($customers);
			Yii::app()->end();
		}
	}

	public function actionGetCustomerDetail()
	{
		$customer_id = $_GET['customer_id'];
		$customer = Customer::model()->findByPk($customer_id);
		if ($customer instanceof Customer)
		{
			$contact = Contact::model()->findByAttributes(array('parent_model' => 'Customer', 'parent_id' => $customer->id));

			echo CJSON::encode($contact->attributes);
			Yii::app()->end();
		}
		else
		{
			throw new CHttpException;
		}
	}

	public function actionSuggestProvince()
	{
		if (Yii::app()->request->isAjaxRequest && isset($_GET['term']))
		{
			$province_data = Province::getProvinceData($_GET['term']);

			$list_province = array();
			foreach ($province_data as $item)
			{
				$list_province[] = array(
					'value' => $item['value'],
					'label' => $item['label'],
					'id' => $item['id']
				);
			}

			$this->setJsonHeader();
			echo CJSON::encode($list_province);
			Yii::app()->end();
		}
	}

	public function actionSuggestCity()
	{
		if (Yii::app()->request->isAjaxRequest && isset($_GET['term']))
		{
			$list_all = array();

			$GETpid = 0;
			if (isset($_GET['pid'])):$GETpid = $_GET['pid'];
			endif;

			$list_district = District::getListDistrict($_GET['term'], $GETpid, TRUE);
			foreach ($list_district as $item)
			{
				$list_all[] = array(
					'value' => $item['value'],
					'label' => $item['label'],
					'did' => $item['id'],
					'zid' => 0,
				);
			}

			$list_zone = Zone::getListZone($_GET['term'], $GETpid, TRUE);
			foreach ($list_zone as $item)
			{
				$list_all[] = array(
					'value' => $item['value'],
					'label' => $item['label'],
					'did' => $item['did'],
					'zid' => $item['zid'],
				);
			}

			$this->setJsonHeader();
			echo CJSON::encode($list_all);
			Yii::app()->end();
		}
	}

	public function actionDomesticService()
	{
		$model = new ShipmentDomestic;
		$weight_to_count = array();
		if (isset($_POST['ShipmentDomestic']) && isset($_POST['ShipmentItem']))
		{
			$shipment_items = $_POST['ShipmentItem'];

			foreach ($shipment_items as $item => $value)
			{
				$weight = $value['package_weight'];
				$height = $value['package_height'];
				$width = $value['package_width'];
				$length = $value['package_length'];

				array_push($weight_to_count, ShipmentItem::getStaticWeightToCount($weight, $height, $width, $length));
			}
			$total_weight = array_sum($weight_to_count);

			$model->attributes = $_POST['ShipmentDomestic'];
			if ($model->validate())
			{
				$services = RateDomestic::getServiceList(1, $model->district_id, $model->zone_id, $total_weight);
				$this->renderPartial('_rate', array(
					'services' => $services,
					'model' => $model,
						)
				);
			}
		}
	}

	public function actionIntraCityService()
	{
		if (isset($_POST['ShipmentItem']) && isset($_POST['ShipmentIntracity']))
		{
			$weight_to_count = array();
			$shipment_items = $_POST['ShipmentItem'];
			$area_id = $_POST['ShipmentIntracity']['area_id'];

			foreach ($shipment_items as $item => $value)
			{
				$weight = $value['package_weight'];
				$height = $value['package_height'];
				$width = $value['package_width'];
				$length = $value['package_length'];

				array_push($weight_to_count, ShipmentItem::getStaticWeightToCount($weight, $height, $width, $length));
			}
			$total_weight = array_sum($weight_to_count);

			$services = IntraCityServices::getServices($total_weight, $area_id);
			$this->renderPartial('_rate_city', array(
				'services' => $services,
					)
			);
		}
	}

	public function actionInternationalService()
	{
		if (isset($_POST['ShipmentItem']) && isset($_POST['ShipmentInternational']) && isset($_POST['ShipmentInternational']['zone']))
		{
			$weight_to_count = array();
			$shipment_items = $_POST['ShipmentItem'];
			$zone = $_POST['ShipmentInternational']['zone'];
			$type = $_POST['Shipment']['type'];

			foreach ($shipment_items as $item => $value)
			{
				$weight = $value['package_weight'];
				$height = $value['package_height'];
				$width = $value['package_width'];
				$length = $value['package_length'];

				array_push($weight_to_count, ShipmentItem::getStaticWeightToCount($weight, $height, $width, $length));
			}
			$total_weight = array_sum($weight_to_count);

			$services = RateInternational::getServices($total_weight, $zone, $type);
			$this->renderPartial('_rate_international', array(
				'services' => $services,
					)
			);
		}
	}

	public function actionSuggestInternationalZoneCountry()
	{
		if (Yii::app()->request->isAjaxRequest && isset($_GET['term']))
		{
			$country_data = ZoneInternational::getAllZoneCuntryData($_GET['term']);

			$list_country = array();
			foreach ($country_data as $item)
			{
				$list_country[] = array(
					'value' => $item['value'],
					'label' => $item['label'],
					'zone' => $item['zone'],
					'id' => $item['id']
				);
			}
			array_push($list_country, array(
				'value' => 'Indonesia',
				'label' => 'Indonesia',
				'zone' => 'no_zone',
				'id' => '0'
			));

			$this->setJsonHeader();
			echo CJSON::encode($list_country);
			Yii::app()->end();
		}
	}

	public function actionView()
	{
		$this->layout = '//layouts/column1';
		$shipment = Yii::app()->user->getState('Shipment');
//		$additional_data = Yii::app()->user->getState('pickup-' . $shipment->service_type);
		$add_costs = Yii::app()->user->getState('Shipment-add_costs');
		$items = Yii::app()->user->getState('Shipment-items');
		$customer = Customer::model()->findByPk($shipment->customer_id);

		if (isset($_POST['yt0']))
		{
			$trans = Yii::app()->db->beginTransaction();
			try
			{
				if ($shipment->save())
				{
//					$additional_data->shipment_id = $shipment->id;
//					$additional_data->save();

					if (is_array($add_costs))
					{
						foreach ($add_costs as $cost)
						{
							$cost->shipment_id = $shipment->id;
							$cost->save();
						}
					}


					if (is_array($items))
					{
						foreach ($items as $item)
						{
							$item->shipment_id = $shipment->id;
							$item->save();
						}
					}
					$trans->commit();
//					$mail = Yii::app()->user->getState('mail');
//					if ($mail)
//					{
//						$pdfname = time();
//						$this->PDFAwb($shipment->awb, $pdfname);
//						$message = new YiiMailMessage;
//						$message->setBody('This is the order data', 'text/html');
//						$message->setTo($contact->email);
//						$message->setSubject('Waybill');
//						$message->setFrom('admin@dcourier.com');
//						$message->attach(Swift_Attachment::frompath(dirname(Yii::app()->basePath) . '/pdf/' . $pdfname . '.pdf'));
//						Yii::app()->mail->send($message);
//					}
					$this->redirect(array('customer/view', 'id' => $shipment->customer_id));
				}
			}
			catch (CDbException $e)
			{
				$trans->rollback();
				throw $e;
			}
		}

		$this->render('view', array(
			'shipment' => $shipment,
			'customer' => $customer
		));
	}

	public function actionGetEstimatedDeliveryDate()
	{
		$inquiry = new InquiryForm;
		if (isset($_POST['InquiryForm']))
		{
			$inquiry->attributes = $_POST['InquiryForm'];

			if ($inquiry->transit_time != '')
			{
				$pickup_stamp = strtotime($inquiry->pickup_date);
				$response = Yii::app()->dateFormatter->format('MM/dd/yyyy', strtotime('+ ' . $inquiry->transit_time . ' day', $pickup_stamp));
			}
			else
			{
				$response = $inquiry->pickup_date;
			}

			echo CJSON::encode($response);
		}
	}

	public function getTransitTime($service_type, $service_id = '', $receiver_zone_code = '', $receiver_city_code = '', $receiver_country_id = '')
	{
		$trans_date = '';
		$trans_time = '';
		switch ($service_type)
		{
			case 'domestic':
				$rate = RateDomestic::model()->findByAttributes(array(
					'service_id' => $service_id,
					'origin_id' => 1,
					'zone_id' => $receiver_zone_code,
					'district_id' => $receiver_city_code,
						));

				if (($rate instanceof RateDomestic))
				{
					$trans_date = Yii::app()->dateFormatter->format('MM/dd/yyyy', strtotime('+' . $rate->transit_time . ' day'));
					$trans_time = $rate->transit_time;
				}
				break;

			case 'city':
				$city_type = IntraCityTypes::model()->findByPk($service_id);
				if (($city_type instanceof IntraCityTypes))
				{
					$trans_date = Yii::app()->dateFormatter->format('MM/dd/yyyy', strtotime('+' . $city_type->transit_time . ' day'));
					$trans_time = $city_type->transit_time;
				}
				break;
			case 'international':
				$zone_country = ZoneInternational::model()->findByPk($receiver_country_id);
				if (($zone_country instanceof ZoneInternational))
				{
					$trans_date = Yii::app()->dateFormatter->format('MM/dd/yyyy', strtotime('+' . $zone_country->transit_time . ' day'));
					$trans_time = $zone_country->transit_time;
				}
				break;
		}
		return array(
			'trans_date' => $trans_date,
			'trans_time' => $trans_time
		);
	}

	public function actionPDFAwb($id)
	{
		if (isset($_GET['id']) && is_numeric($id))
		{
			$shipment = Shipment::model()->findByPk($id);
			if($shipment instanceof Shipment)
			{
				$customer = Customer::model()->findByPk($shipment->customer_id);
				$contact = $customer->getContactData();
				$html2pdf = Yii::app()->ePdf->HTML2PDF('P','A4','en');
				$html2pdf->WriteHTML($this->renderPartial('pdfawb', array('shipment'=>$shipment,'customer'=>$customer,'contact'=>$contact), true));
				$html2pdf->Output(dirname(Yii::app()->basePath) . '/pdf/tes2.pdf', EYiiPdf::OUTPUT_TO_BROWSER);
			}
		}
	}

//	public function actionGetRates()
//	{
//		if (Yii::app()->request->isAjaxRequest)
//		{
//			$flag = 1;
//			if (isset($_POST['country_id'])):$POSTcid = $_POST['country_id'];
//			else:$POSTcid = false;
//			endif;
//			if (isset($_POST['zone_country'])):$POSTzc = $_POST['zone_country'];
//			else:$POSTzc = false;
//			endif;
//			if (isset($_POST['zone_id'])):$POSTzid = $_POST['zone_id'];
//			else:$POSTzid = false;
//			endif;
//			if (isset($_POST['district_id'])):$POSTdid = $_POST['district_id'];
//			else:$POSTdid = false;
//			endif;
//			if (isset($_POST['postcode'])):$POSTposcode = $_POST['postcode'];
//			else:$POSTposcode = false;
//			endif;
//
//			if ($POSTzc == 'no_zone')
//			{
//				$criteria = new CDbCriteria;
//				$criteria->compare('origin_id', 1);
//
//				if ($POSTposcode != '')
//				{
//					$dataArea = Area::getZoneID($POSTposcode, 'postcode');
//					if (!(!$dataArea))
//					{
//						if (RateDomestic::model()->countByAttributes(array('zone_id' => $dataArea['zone_id'])) > 0)
//							$criteria->compare('zone_id', $dataArea['zone_id']);
//						else
//						{
//							$criteria->compare('zone_id', 0);
//							$criteria->compare('district_id', $dataArea['district_id']);
//						}
//					}
//					else
//						$flag = 0;
//				}
//				else if ($POSTposcode == '')
//				{
//					$criteria->compare('zone_id', $POSTzid);
//					$criteria->compare('district_id', $POSTdid);
//				}
//
//				$rates = new CActiveDataProvider('RateDomestic', array(
//							'criteria' => $criteria,
//							'pagination' => false
//						));
//
//				$this->renderPartial('_rates_domestic', array(
//					'rates' => $rates,
//					'flag' => $flag,
//				));
//			}
//			else
//			{
//				$country = ZoneInternational::model()->findByPk($POSTcid);
//				$rates = new CActiveDataProvider('RateInternational', array(
//							'criteria' => array(
//								'condition' => 'weight < 10',
//								'order' => 'type'
//							),
//							'pagination' => false
//						));
//
//				$this->renderPartial('_rates_international', array(
//					'rates' => $rates,
//					'flag' => $flag,
//					'zone' => $POSTzc,
//					'country' => $country
//				));
//			}
//		}
//	}

	public function actionGetShippingCosts()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			if ((isset($_POST['Shipment']) && isset($_POST['InquiryForm'])) && isset($_POST['ShipmentItem']))
			{
				$inquiry = new InquiryForm;
				$inquiry->setAttributes($_POST['InquiryForm']);

				$shipment = new Shipment;
				$shipment->setAttributes($_POST['Shipment']);

				$shipment_items = $_POST['ShipmentItem'];
				$items = array();
				$weight_to_count = array();
				$total_weight = 0;
				foreach ($shipment_items as $item)
				{
					$shipment_item = new ShipmentItem();
					$shipment_item->setScenario('inquiry');
					$shipment_item->attributes = $item;

					if ($shipment_item->validate())
					{
						array_push($weight_to_count, ShipmentItem::getStaticWeightToCount($shipment_item->package_weight, $shipment_item->package_height, $shipment_item->package_width, $shipment_item->package_length));
					}
					$total_weight = array_sum($weight_to_count);
				}

				switch ($shipment->service_type)
				{
					case 'domestic':
						$rate_price = RateDomestic::getRatePriceBaseOnId($inquiry->domestic_ratePrice_id, $total_weight);
						break;
					case 'international':
						$rate_price = RateInternational::getRatePrice($shipment->service_id, $total_weight, $shipment->type, $inquiry->receiver_country_code);
						break;
				}

				if (!isset($rate_price))
					$rate_price = 0;

				$inquiry->freight_charges = $rate_price;
				$inquiry->fuel_charges = $rate_price * 0.24;
				$inquiry->vat = ($inquiry->freight_charges + $inquiry->fuel_charges) * 0.01;
				$inquiry->shipping_charges = $inquiry->freight_charges + $inquiry->vat;

				echo CJSON::encode(array(
					'freight_charges' => $inquiry->freight_charges,
					'fuel_charges' => $inquiry->fuel_charges,
					'vat' => $inquiry->vat,
					'shipping_charges' => $inquiry->shipping_charges
				));
				Yii::app()->end();
			}
		}
	}

	public function actionGetTotalCharges()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			if (isset($_GET['InquiryForm']) && isset($_GET['ShipmentAdditionalCharge']))
			{
				$shipment_costs = $_GET['ShipmentAdditionalCharge'];
				$costs = array();
				$arr_costs = array();

				foreach ($shipment_costs as $cost)
				{
					$shipment_costs = new ShipmentAdditionalCharge();
					$shipment_costs->setScenario('inquiry');

					$shipment_costs->attributes = $cost;
					if ($shipment_costs->validate())
					{
						$costs[] = $shipment_costs;
						array_push($arr_costs, $shipment_costs->cost);
					}
				}
				$total_add_cost = array_sum($arr_costs);

				$inquiry = new InquiryForm;
				$inquiry->setAttributes($_GET['InquiryForm']);
				$total = $total_add_cost + $inquiry->cod + $inquiry->shipping_charges;
				echo CJSON::encode($total);
				Yii::app()->end();
			}
		}
	}

	public function actionCekRate($customer_id = null)
	{
		$inquiry = new InquiryForm('api-rate');

		$inquiry->shipper_country = 'Indonesia';
		$inquiry->shipper_city = 'Jakarta';
		$inquiry->receiver_country = 'Indonesia';
		$inquiry->receiver_city = 'Jakarta';
		$inquiry->package_weight = 1;

		$customer = new Customer;
		if (!is_null($customer_id) && is_numeric($customer_id))
			$customer = Customer::model()->findByPk($customer_id);

		$rates = new CArrayDataProvider(array());

		$this->render('cekRate', array(
			'inquiry' => $inquiry,
			'rates' => $rates,
			'customer' => $customer
		));
	}

	public function actionGetRates()
	{
		if (Yii::app()->request->isAjaxRequest && isset($_GET['Cek']))
		{
			$inquiry = new InquiryForm('api-rate');
			$inquiry->setAttributes($_GET['Cek']);
			$customer_id = null;
			$use_rate = array();
			$rates = array();
			if (isset($_GET['customer_id']) && is_numeric($_GET['customer_id']))
			{
				$customer_id = $_GET['customer_id'];
				$customer = Customer::model()->findByPk($customer_id);
				if (($customer instanceof Customer))
				{
					$customer_id = $customer->id;
					$customer_rates = CustomerDiscount::model()->findAllByAttributes(array(
						'customer_id' => $customer->id,
						'use_rate' => 1
							));
					foreach ($customer_rates as $cus_rate)
					{
						array_push($use_rate, $cus_rate->service_id);
					}
				}
			}

			if (strtolower($inquiry->receiver_country) != 'indonesia')
			{
				$country = ZoneInternational::model()->findByAttributes(array('country' => strtolower($inquiry->receiver_country)));
				if (($country instanceof ZoneInternational))
				{
					$rates = RateInternational::getServices($inquiry->package_weight, $country->zone, $country->transit_time, $customer_id, $use_rate);
					$product = 'International';
				}
			}
			else
			{
				$routing = IntraCityRouting::model()->findByAttributes(array('postcode' => $inquiry->receiver_postal));
				if ($routing instanceof IntraCityRouting)
				{
					$rates1 = RateCity::getCityRate(ProductService::ProductCityCourier, $routing->code, $inquiry->package_weight, $customer_id, $use_rate);
					$rates2 = array();
					$area = Area::getZoneID($inquiry->receiver_postal, 'postcode');
					if ($area)
					{
						$rates2 = RateDomestic::getServiceList(1, $area['district_id'], $area['zone_id'], $inquiry->package_weight, ProductService::ProductCityCourier, $customer_id, $use_rate);
						$rates = array_merge($rates1, $rates2);
					}
					else
					{
						$rates = $rates1;
					}
					$product = 'City Courier';
				}
				else
				{
					$area = Area::getZoneID($inquiry->receiver_postal, 'postcode');
					if ($area)
					{
						$rates = RateDomestic::getServiceList(1, $area['district_id'], $area['zone_id'], $inquiry->package_weight, ProductService::ProductDomestic, $customer_id, $use_rate);
					}
					$product = 'Domestic';
				}
			}

			$data = new CArrayDataProvider($rates);
//			CVarDumper::dump($rates,10,true);exit;
			$this->renderPartial('_rates', array('rates' => $data, 'product' => $product, 'customer_id' => $customer_id, 'postal' => $inquiry->receiver_postal, 'country' => $inquiry->receiver_country, 'product' => $product,'package_weight'=>$inquiry->package_weight),false,true);
			Yii::app()->end();
		}
	}

	public function actionSuggestDistrict()
	{
		if (Yii::app()->request->isAjaxRequest && isset($_GET['term']))
		{
			$list_district = District::getListDistrict($_GET['term']);
			$this->setJsonHeader();
			echo CJSON::encode($list_district);
			Yii::app()->end();
		}
	}

	public function actionSuggestPostal()
	{
		if (Yii::app()->request->isAjaxRequest && (isset($_GET['term']) && isset($_GET['district'])))
		{
			$district = $_GET['district'];
			$area = $_GET['term'];
			$list_postcode = District::getListPostcode($area, $district);
			$this->setJsonHeader();
			echo CJSON::encode($list_postcode);
			Yii::app()->end();
		}
	}

	public function actionCreateAWB($customer_id = null)
	{
		$this->layout = '//layouts/column1';
		$shipment = new Shipment;
		$customer = new Customer;

		$shipment->shipper_city = 'Jakarta';
		$shipment->shipper_country = 'Indonesia';
		$shipment->receiver_city = 'Jakarta';
		$shipment->receiver_country = 'Indonesia';

		if ($customer_id && is_numeric($customer_id))
		{
			$customer = Customer::model()->findByPk($customer_id);
		}
		if (isset($_GET['Shipment']))
		{
			$shipment->setAttributes($_GET['Shipment']);
		}

		$this->render('createAWB', array(
			'shipment' => $shipment,
			'customer' => $customer,
		));
	}

	public function actionUpdateAWB($id)
	{
		$this->layout = '//layouts/column1';
		$shipment = $this->loadModel($id);
		if (!($shipment->customer_id))
			$customer = new Customer;
		else
			$customer = Customer::model()->findByPk($shipment->customer_id);
		$this->render('updateAWB', array(
			'shipment' => $shipment,
			'customer' => $customer,
		));
	}

	public function actionGetExtCustomerData()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			if (isset($_POST['accountnr']))
			{
				$result = array('status' => 'error', 'data' => '');
				$data = array();
				$customer = Customer::model()->findByAttributes(array('accountnr' => $_POST['accountnr']));
				if ($customer instanceof Customer)
				{
					$contact = $customer->getContactData();
					$data['customer_id'] = $customer->id;
					$data['name'] = $customer->name;
					$data['address'] = $contact->address;
					$data['phone'] = $contact->phone1;
					$data['country'] = $contact->country;
					$data['postal'] = $contact->postal;

					$result['status'] = 'success';
					$result['data'] = $data;
				}
				echo CJSON::encode($result);
				Yii::app()->end();
			}
		}
	}

	public function actionGetExtAvailableProduct()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			if (isset($_POST['company_id']))
			{
				$result = array('status' => 'error', 'data' => '');
				$data = array();
				$product_service = RateCompanyService::model()->findByAttributes(array('company_id' => $_POST['company_id']));
				if ($product_service instanceof RateCompanyService)
				{
					$data['service_type'] = $product_service->productService->product->name;

					$result['status'] = 'success';
					$result['data'] = $data;
				}
				echo CJSON::encode($result);
				Yii::app()->end();
			}
		}
	}

	public function actionGetExtAltRoutingCode()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$routings_city = '';
			$routings = '';
			$result = array('status' => 'error', 'data' => '');
			$data = array();
			if (isset($_POST['product']) && isset($_POST['receiver_postal']))
			{
				$product = $_POST['product'];
				$receiver_postal = $_POST['receiver_postal'];
				if ($product == 'City Courier')
				{
					$routings = RateCompany::model()->findAllByAttributes(array('is_city' => 1), 'id!=5');
					$routings_city = IntraCityRouting::model()->findByAttributes(array('postcode' => $receiver_postal));
				}
				else if ($product == 'Domestic')
					$routings = RateCompany::model()->findAllByAttributes(array('is_domestic' => 1));
				else if ($product == 'International')
					$routings = RateCompany::model()->findAllByAttributes(array('is_international' => 1));

				if (count($routings) != 0)
				{
					foreach ($routings as $routing)
						array_push($data, array('code' => $routing->code));

					if ($routings_city instanceof IntraCityRouting)
						array_push($data, array('code' => $routings_city->code));

					$result = array('status' => 'success', 'data' => $data);
				}

				echo CJSON::encode($result);
				Yii::app()->end();
			}
		}
	}

	public function actionGetExtRoutingCode()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$result = array('status' => 'error', 'data' => '');
			$data = array();
			if (isset($_POST['country']) && isset($_POST['postal']))
			{
				$country = ucfirst($_POST['country']);
				$postal = $_POST['postal'];
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
						$result = array('status' => 'success', 'data' => $data, 'service_type' => 'City-Courier');
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
	}

	public function actionGetExtTypeService()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$result = array('status' => 'error', 'data' => '');
			$data = array();
			if (isset($_POST['Shipment']))
			{
				$shipment = new Shipment;
				$shipment->setAttributes($_POST['Shipment']);
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
	}

	public function actionGetExtRate()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$result = array('status' => 'error', 'data' => '');
			$data = array();
			if (isset($_POST['Shipment']))
			{
				$shipment = new Shipment;
				$shipment->setAttributes($_POST['Shipment']);
				switch ($shipment->service_type)
				{
					case 'City Courier':
						$rate = RateCity::model()->findByAttributes(array('service_id' => $shipment->service_id));
						if (($rate instanceof RateCity))
						{
							$price = $rate->price * RateCity::increment($shipment->package_weight, $rate->weight_inc);
						}
						else
						{
							$area = Area::getZoneID($shipment->receiver_postal);
							$price = RateDomestic::getRatePrice($shipment->service_id, 1, $area['district_id'], $area['zone_id'], $shipment->package_weight);
						}
						$result = array('status' => 'success', 'data' => $price);
						break;

					case 'Domestic':
						$area = Area::getZoneID($shipment->receiver_postal);
						$price = RateDomestic::getRatePrice($shipment->service_id, 1, $area['district_id'], $area['zone_id'], $shipment->package_weight);
						$result = array('status' => 'success', 'data' => $price);
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
							$price = RateInternational::getRatePrice($shipment->service_id, $shipment->package_weight, $shipment->type, $zone);
							$result = array('status' => 'success', 'data' => $price);
						}
						break;

					case '':
						continue;
				}
			}
			echo CJSON::encode($result);
			Yii::app()->end();
		}
	}

	public function actionSubmitOrder()
	{
		$result = array('success' => 'false', 'message' => '');
		$data = array();
		if (isset($_POST['Shipment']))
		{
			if ($_POST['Shipment']['id'] == '')
				$shipment = new Shipment;
			else
				$shipment = $this->loadModel($_POST['Shipment']['id']);
			$shipment->setAttributes($_POST['Shipment']);

			if ($shipment->save())
			{
				$data = array(
					'awb' => $shipment->awb,
					'shipment_id'=>$shipment->id
				);
				$result = array('success' => true, 'message' => $data);
			}
			else
			{
				$result = array('success' => false, 'message' => $shipment->getErrors());
			}
		}
		echo CJSON::encode($result);
		Yii::app()->end();
	}
	
	public function actionGetAwbNumber()
	{
		$shipment = new Shipment;
		$result = array('status' => 'error', 'data' => '');
		$shipment->awb = '90' . rand(10000000, 99999999);
		while (!$shipment->validate(array('awb')))
		{
			$shipment->awb = '90' . rand(10000000, 99999999);
		}
		
		echo CJSON::encode($result = array('status' => 'success', 'data' => $shipment->awb));
		Yii::app()->end();
	}
}