<?php

class PickupController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($shipment_id)
	{
		$model=new Pickup;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$shipment = Shipment::model()->findByPk($shipment_id);
		
		$criteria = new CDbCriteria;
		$criteria->with = array('user','pickup');
		$driver_list_available = new CActiveDataProvider('Driver', array(
			'criteria' => $criteria
		));
		
		if(isset($_GET['driver_id']))
		{
			$model->driver_id=$_GET['driver_id'];
			$model->shipment_id = $shipment_id;
			$trans = Yii::app()->db->beginTransaction();
			try
			{
				if($model->save())
				{
					$event = new ShipmentEvent;
					$event->shipment_id = $shipment_id;
					$event->event_time = time();
					$event->with_mde = 0;
					$event->user_id = Yii::app()->user->id;
					$event->status = 11;
					$event->setScenario('order');
					if($event->save())
					{
						$shipment->shipping_status = 11;
						$shipment->setScenario('event');
						if($shipment->save())
						{
							$trans->commit();
							$this->redirect(array('shipment/operation'));
						}
						else
							throw new CException($shipment->getErrors());
					}
					else
						throw new CException($event->getErrors());
				}
				else
					throw new CException($model->getErrors());
			}
			catch (CException $e)
			{
				$trans->rollback();
				throw $e;
			}
		}
		$this->render('create',array(
			'model'=>$model,
			'driver_list_available'=>$driver_list_available,
			'shipment_id'=>$shipment_id
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($shipment_id)
	{
		$model=  Pickup::model()->findByAttributes(array('shipment_id'=>$shipment_id));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$shipment = Shipment::model()->findByPk($shipment_id);
		
		$criteria = new CDbCriteria;
		$criteria->with = array('user','pickup');
		$driver_list_available = new CActiveDataProvider('Driver', array(
			'criteria' => $criteria
		));
		
		if(isset($_GET['driver_id']))
		{
			$model->driver_id=$_GET['driver_id'];
			$model->shipment_id = $shipment_id;
			$trans = Yii::app()->db->beginTransaction();
			try
			{
				if($model->save())
				{
					$event = new ShipmentEvent;
					$event->shipment_id = $shipment_id;
					$event->event_time = time();
					$event->with_mde = 0;
					$event->user_id = Yii::app()->user->id;
					$event->status = 11;
					$event->setScenario('order');
					if($event->save())
					{
						$shipment->shipping_status = 11;
						$shipment->setScenario('event');
						if($shipment->save())
						{
							$trans->commit();
							$this->redirect(array('shipment/operation'));
						}
						else
							throw new CException($shipment->getErrors());
					}
					else
						throw new CException($event->getErrors());
				}
				else
					throw new CException($model->getErrors());
			}
			catch (CException $e)
			{
				$trans->rollback();
				throw $e;
			}
		}
		$this->render('update',array(
			'model'=>$model,
			'driver_list_available'=>$driver_list_available,
			'shipment_id'=>$shipment_id
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Pickup');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Pickup('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Pickup']))
			$model->attributes=$_GET['Pickup'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Pickup::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='pickup-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
