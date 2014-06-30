<?php

class CheckJneStatusCommand extends TzConsoleCommand
{
	public function actionIndex()
	{
		// Set appropriate path & library
		$vendors_path=Yii::app()->getBasePath().'/vendors';
		set_include_path(get_include_path().PATH_SEPARATOR.$vendors_path);
		require_once 'Zend/Http/Client.php';
		
		// Get vendor params
		$jne_params=Yii::app()->params->vendors['JNE'];
		$client=new Zend_Http_Client(null, array(
			'strict'=>false,
			'adapter' => 'Zend_Http_Client_Adapter_Curl',
      'persistent' => true,
		));
		// Set username & key
		$client->setParameterPost(array(
			'username'=>$jne_params['api_username'],
			'api_key'=>$jne_params['api_key'],
		));
		// Base URL
		$base_url=$jne_params['api_url'];
		// Get all incomplete shipments
		$this->printf('Getting all incomplete shippings');
		$shipments = $this->getAllJneIncompleteOrders();
		$this->printf('Got %d shippings to process',count($shipments));
		
		foreach($shipments as $shipment)
		{	
			if(!empty($shipment->carrier_awb))
			{
				$awb_jne = $shipment->carrier_awb;
				$url=$base_url.$awb_jne.'/format/json';
				$this->printf('Processing order #%d with AWB: %s, calling API: %s',$shipment->id,$awb_jne,$url);
				$client->setUri($url);
				$t1=microtime(true);
				$response=$client->request('POST');
				$t2=microtime(true);
				$this->printf('API called in %.5f seconds',$t2-$t1);
				if($response->isSuccessful()) 
				{
					$this->printf('API response successful');
					$json = json_decode($response->getBody(), true);
					
					$status = isset($json['cnote']['pod_status']) ? $json['cnote']['pod_status'] : null;
					$recip = isset($json['cnote']['cnote_pod_receiver']) ? $json['cnote']['cnote_pod_receiver'] : 'N/A';
					$this->printf('Received status: %s',$status);
					print_r($json);
//					$this->setJneStatusOrder($shipment, $status, $recip);
				} 
				else
				{
					$this->printf('API response error #%d: %s',$response->getStatus(),$response->getMessage());
					$json = json_decode($response->getBody(), true);
					if($json!==false)
					{
						$this->printf("Dumping JSON");
						var_export($json);
					}
				}
			}
		}
	}
	
	/**
	 * Return all incomplete JNE orders
	 * 
	 * @return Shipment[]
	 */
	private function getAllJneIncompleteOrders()
	{
		$jne_service_ids = RateCompanyService::getJNEServiceId();
		
		$criteria = new CDbCriteria;
		$criteria->params = array(':status_POD' => ShipmentStatus::POD);
		$criteria->condition = 'shipping_status != :status_POD AND carrier_awb !=""';
		$criteria->addInCondition('service_id', $jne_service_ids);

		return Shipment::model()->findAll($criteria);
	}
	
	/**
	 * Set shipment status to completed
	 * 
	 * @param Shipment $shipment
	 * @param string $recepient_name
	 */
	private function setJneStatusOrder(Shipment $shipment,$status,$recepient_name = '')
	{
		$event = new ShipmentEvent;
		$shipment->setScenario('event');
		
		$event->created=time();
		$event->event_time = $event->created;
		$event->shipment_id=$shipment->id;
		$event->user_id=User::USER_SYSTEM;
		switch (strtoupper($status))
		{
			case 'DELIVERED':
				$event->status=ShipmentStatus::POD;
				$shipment->shipping_status = ShipmentStatus::POD;
				$shipment->event_time = $event->event_time;
				$shipment->recipient_name = $recepient_name;
				break;
			
			case 'MANIFESTED':
				$event->status=ShipmentStatus::MDE;
				$shipment->shipping_status = ShipmentStatus::MDE;
				$shipment->event_time = $event->event_time;
				break;
			
			case 'RECEIVED ON DESTINATION':
				$event->status=ShipmentStatus::ARR;
				$shipment->shipping_status = ShipmentStatus::ARR;
				$shipment->event_time = $event->event_time;
				break;
			
			case 'ON PROCESS':
				$event->status=ShipmentStatus::OTW;
				$shipment->shipping_status = ShipmentStatus::OTW;
				$shipment->event_time = $event->event_time;
				break;
		}
		try 
		{
			$trans = Yii::app()->db->beginTransaction();
			if($event->save())
			{
				if($shipment->save())
				{
					$trans->commit();
					$this->printf('Shipment set to %s',$status);
					return true;
				}
				else
				{
					print_r($shipment->getErrors());
					throw new CException();
				}
			}
			else
			{
				print_r($event->getErrors());
				throw new CException();
			}
		}
		catch (CException $e)
		{
			$trans->rollback();
			throw $e;
		}
	}
}