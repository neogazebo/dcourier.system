<?php

class MobileController extends CController
{
	private $token = null;

	public function init()
	{
		header('Access-Control-Allow-Origin:*');
		header('Content-type: application/json');
		parent::init();
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

	public function actionLogin()
	{
		if (!isset($_REQUEST['username']))
		{
			echo CJSON::encode($this->statusError('form not available'));
			Yii::app()->end();
		}
		$user = User::model()->findByAttributes(array('username' => $_REQUEST['username']));
		if ($user == null)
			echo CJSON::encode($this->statusError('Username null'));
		else
		{
			$driver = Driver::model()->findByPk($user->id);
			if ($driver == null)
				echo CJSON::encode($this->statusError('User not assign Driver'));
			if (!$user->validatePassword($_REQUEST['password']))
				echo CJSON::encode($this->statusError('Username or password is wrong'));
			else
			{

				echo CJSON::encode($this->statusSuccess(array(
							'access_token' => Driver::generateToken($user->id),
							'message' => $driver->message
						)));
			}
		}
		Yii::app()->end();
	}

	public function actionUpdatestatus()
	{
		if (!isset($_REQUEST['token']) || !isset($_REQUEST['message']))
		{
			echo CJSON::encode($this->statusError('Check your parameters'));
			Yii::app()->end();
		}
		$driver = Driver::model()->findByAttributes(array('token' => $_REQUEST['token']));
		$driver->message = $_REQUEST['message'];
		$driver->save(false);

		echo CJSON::encode($this->statusSuccess(1));
	}

	public function actionCheckstatusawb()
	{
		$shipment = Shipment::model()->findByAttributes(array('awb' => $_REQUEST['awb']));
		if ($shipment == null)
			echo CJSON::encode($this->statusError(0));
		else
			echo CJSON::encode($this->statusSuccess(array('shipment_id' => $shipment->id)));
	}

	public function actionChangestatusawb()
	{
		$driver = Driver::model()->findByAttributes(array('token' => $_REQUEST['token']));
		$awbs = explode("|", $_REQUEST['awb']);
		$retAwb = array();
		foreach ($awbs as $awb)
		{
			$shipment = Shipment::model()->findByAttributes(array('awb' => $awb));
			if ($shipment == null)
				continue;
			$retAwb[] = $awb;
			$shipment->shipping_status = isset($_REQUEST['shipment_status']) ? $_REQUEST['shipment_status'] : null;
			$shipment->shipping_remark = isset($_REQUEST['shipment_status']) ? $_REQUEST['shipment_status'] : null;
			$shipment->recipient_name = isset($_REQUEST['recipient_name']) ? $_REQUEST['recipient_name'] : '';
			$shipment->recipient_title = isset($_REQUEST['recipient_title']) ? $_REQUEST['recipient_title'] : '';
			$shipment->save(false);

			if (isset($_REQUEST['image']))
			{
				$potoBarang = new PotoBarang();
				$potoBarang->time = time();
				$potoBarang->users_id = $driver->user_id;
				$potoBarang->image = $_REQUEST['image'];
				$potoBarang->awb = $awb;
				$potoBarang->shipment_id = $shipment->id;
				$potoBarang->Save(false);
			}

			$shipmentEvent = new ShipmentEvent;
			$shipmentEvent->shipment_id = $shipment->id;
			$shipmentEvent->user_id = $driver->user_id;
			$shipmentEvent->status = isset($_REQUEST['shipment_status']) ? $_REQUEST['shipment_status'] : null;
			$shipmentEvent->created = time();
			$shipmentEvent->event_time = time();
			$shipmentEvent->description = isset($_REQUEST['keterangan']) ? $_REQUEST['keterangan'] : '';
			$shipmentEvent->save(false);
		}
		echo CJSON::encode($this->statusSuccess($retAwb));
	}

	public function actionAssignlist()
	{
		if (!isset($_REQUEST['token']))
			echo CJSON::encode($this->statusError(0));

		$booking = Booking::model()->findAllByAttributes(array('driver_id' => $driver = Driver::getUserId($_REQUEST['token'])));
		$arr = array();
		foreach ($booking as $row)
		{
				$arr[] = $row->booking_code;
		}
		echo CJSON::encode($this->statusSuccess($arr));
	}
	/*
	 * $token
	 * $awb
	 */

	public function actionAssignapprove()
	{
		if (!isset($_REQUEST['token']) && !isset($_REQUEST['awb']))
			echo CJSON::encode($this->statusError(0));
		else
		{
			$driver = Driver::getUserId($_REQUEST['token']);

			$shipment = Shipment::model()->findByAttributes(array('awb' => $_REQUEST['awb'], 'shipping_status' => 11));
			if ($shipment == null)
				echo CJSON::encode($this->statusError('shippment null'));
			else
			{
				$shipment->shipping_status = 2;
				$shipment->save(false);
				$shipmentEvent = new ShipmentEvent;
				$shipmentEvent->shipment_id = $shipment->id;
				$shipmentEvent->user_id = $driver;
				$shipmentEvent->status = 2;
				$shipmentEvent->created = time();
				$shipmentEvent->event_time = time();
				$shipmentEvent->description = 'Kurir Approve';
				$shipmentEvent->save(false);
				echo CJSON::encode($this->statusSuccess(1));
			}
		}
		Yii::app()->end();
	}
	/*
	 * $token
	 * $awb
	 * $keterangan
	 */

	public function actionAssigncancel()
	{
		if (!isset($_REQUEST['token']) && !isset($_REQUEST['awb']))
			echo CJSON::encode($this->statusError(0));
		else
		{
			$driver = Driver::getUserId($_REQUEST['token']);

			$shipment = Shipment::model()->findByAttributes(array('awb' => $_REQUEST['awb'], 'shipping_status' => 11));
			if ($shipment == null)
				echo CJSON::encode($this->statusError('shippment null'));
			else
			{
				$shipment->shipping_status = 1;
				$shipment->save(false);
				$shipmentEvent = new ShipmentEvent;
				$shipmentEvent->shipment_id = $shipment->id;
				$shipmentEvent->user_id = $driver;
				$shipmentEvent->status = 1;
				$shipmentEvent->created = time();
				$shipmentEvent->event_time = time();
				$shipmentEvent->description = isset($_REQUEST['keterangan']) ? $_REQUEST['keterangan'] : '';
				$shipmentEvent->save(false);

				$pickup = Pickup::model()->findByAttributes(array('shipment_id' => $shipment->id));
				$pickup->delete(false);
				echo CJSON::encode($this->statusSuccess(1));
			}
		}
		Yii::app()->end();
	}

	public function actionInsertdelivery()
	{
		$driver = Driver::getUserId($_REQUEST['token']);
		$awb = $_REQUEST['awb'];
		$awbs = explode('|', $awb);
		$return = array();
		foreach ($awbs as $awb)
		{
			$return[] = $this->insertdelivery($driver, $awb);
		}
		echo CJSON::encode($this->statusSuccess($return));
	}

	public function insertdelivery($userId = null, $awb = null)
	{
		if ($awb == null || $awb == '')
			return FALSE;
		$checkDeliveryList = KurirDeliveryList::model()->findByAttributes(array('awb'=>$awb));
		if($checkDeliveryList != null)
			return FALSE;
		$checkShipement = Shipment::model()->findByAttributes(array('awb'=>$awb));
		if($checkShipement==null)
			return FALSE;
		$model = new KurirDeliveryList;
		$model->created = time();
		$model->user_id = $userId;
		$model->awb = $awb;
		$model->save(false);
		return $awb;
	}

	public function actionDeliverylist()
	{
		$driver = Driver::getUserId($_REQUEST['token']);
		$lists = KurirDeliveryList::model()->findAllByAttributes(array('user_id'=>$driver));
		$return = array();
		foreach ($lists as $list){
			$return[] = array($list->awb,  $this->getShipmentByAwb($list->awb, 'receiver_address'));
		}
		echo CJSON::encode($this->statusSuccess($return));
	}
	protected function getShipmentByAwb($awb,$field){
		$model = Shipment::model()->findByAttributes(array('awb'=>$awb));
		if($model==null)
			return false;
		return $model->receiver_address;
			
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