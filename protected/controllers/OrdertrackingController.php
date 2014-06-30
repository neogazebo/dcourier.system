<?php

class OrdertrackingController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column1';

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model = new OrderTracking('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['OrderTracking']))
			$model->attributes = $_GET['OrderTracking'];
		if (isset($_POST['yt0']))
		{
			$model->attributes = $_POST['OrderTracking'];
			$this->redirect(array('trackingDetails', 'awb' => $model->order_id));
		}

		$this->render('index', array(
			'model' => $model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = OrderTracking::model()->findByPk($id);
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
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'order-tracking-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionRequest()
	{
		$aSmartupdater = json_decode($_SERVER['HTTP_X_SMARTUPDATER'], true);
		$xheader = false;
		if ((int) $aSmartupdater["timeout"] < 5000)
		{
			$xheader = '{"timeout":"5000"}';
		}
		if ((int) $aSmartupdater["timeout"] > 15000)
		{
			$xheader = '{"timeout":"15000"}';
		}

		if ($xheader)
		{
			header("X-Smartupdater:$xheader");
		}
		echo CJSON::encode(array('success' => true));
		flush();
		Yii::app()->end();
	}

	public function actionTrackingDetails($id)
	{
		$this->layout = '//layouts/column2';
		$shipment = Shipment::model()->findByPk($id);
		$customer = Customer::model()->findByPk($shipment->customer_id);
		$criteria = new CDbCriteria;
		$criteria->compare('shipment_id', $shipment->id);

		$list_event = new CActiveDataProvider('ShipmentEvent', array(
					'criteria' => $criteria,
				));
		$new_event = new ShipmentEvent;
		$new_event->event_time = date('m/d/Y H:i', time());
		$new_event->status = $shipment->shipping_status;
		$new_event->recipient_name = $shipment->recipient_name;
		$new_event->recipient_title = $shipment->recipient_title;

		if ($shipment->recipient_date != '')
			$new_event->recipient_date = date('m/d/Y H:i', $shipment->recipient_date);

		if (isset($_POST['ShipmentEvent']))
		{
			$new_event->setAttributes($_POST['ShipmentEvent']);
			$shipment->shipping_status = $new_event->status;
			$shipment->shipping_remark = $new_event->remark;
			$shipment->recipient_name = $new_event->recipient_name;
			$shipment->recipient_title = $new_event->recipient_title;
			$shipment->event_time = strtotime($new_event->event_time);
			if (isset($new_event->recipient_date))
				$shipment->recipient_date = strtotime($new_event->recipient_date);
			$shipment->setScenario('event');
			$trans = Yii::app()->db->beginTransaction();
//			error_log(strtotime($new_event->recipient_date).' - '.$new_event->recipient_date);
			error_log($shipment->recipient_date);
			try
			{
				if ($new_event->save())
				{
					error_log($shipment->recipient_date);
					if ($shipment->save())
					{
						error_log($shipment->recipient_date);
						Yii::app()->user->setFlash('success', 'Order status has successfully updated');
						$trans->commit();
						$this->redirect(array('trackingDetails', 'id' => $id));
					}
					else
					{
//						throw new CException(var_export($shipment->getErrors()));
						CVarDumper::dump($shipment->getErrors(), 10, true);
						exit;
					}
				}
				else
					throw new CException(var_export($new_event->getErrors()));
			}
			catch (CException $e)
			{
				$trans->rollback();
				throw $e;
			}
		}

		$this->render('view', array(
			'list_event' => $list_event,
			'new_event' => $new_event,
			'shipment' => $shipment,
			'customer' => $customer
		));
	}

	public function actionSelectRemarks()
	{
		if (isset($_POST['status_id']))
		{
			$POSTstatus_id = $_POST['status_id'];
			$remarks = ShipmentRemark::model()->findAllByAttributes(array('status_id' => $POSTstatus_id));
			$this->renderPartial('_selectRemarks', array(
				'remarks' => $remarks,
				'status_id' => $POSTstatus_id,
					), false, true);
		}
	}

	public function actionUpdateBulkStatus()
	{
		$new_event = new ShipmentEvent('bulkinsert');
		$failed = array();
		$success = array();

		if (isset($_POST['ShipmentEvent']))
		{
			$new_event->setAttributes($_POST['ShipmentEvent']);

			if ($new_event->validate())
			{
				$rawsAwb = nl2br($new_event->shipment_list);
				$arrAwb = explode('<br />', $rawsAwb);
				foreach ($arrAwb as $awb)
				{
					$awb = trim($awb);
					$shipment = Shipment::model()->findByAttributes(array('awb' => $awb));
					if($shipment instanceof Shipment)
					{
						array_push($success, $awb);
//						CVarDumper::dump($success,10,true);
						$event = new ShipmentEvent;
						$event->setAttributes($_POST['ShipmentEvent']);
						$event->setAttribute('shipment_id', $shipment->id);

						$shipment->shipping_status = $event->status;
						$shipment->shipping_remark = $event->remark;
						$shipment->recipient_name = $event->recipient_name;
						$shipment->recipient_title = $event->recipient_title;
						$shipment->event_time = strtotime($event->event_time);
						if (isset($event->recipient_date))
							$shipment->recipient_date = strtotime($event->recipient_date);
						$shipment->setScenario('event');
						$event->save();
						$shipment->save();
					}
					else
					{
						array_push($failed, $awb);
					}
					if(count($success) != 0)
					{
						$list_success =  implode(', ', $success);
						Yii::app()->user->setFlash('success','Success : '.$list_success);
					}
					if(count($failed) != 0)
					{
						$list_failed = implode(', ', $failed);
						Yii::app()->user->setFlash('error', 'Failed : '.$list_failed);
					}
				}
			}
		}

		$this->render('updateBulk', array(
			'new_event' => $new_event
		));
	}
}
