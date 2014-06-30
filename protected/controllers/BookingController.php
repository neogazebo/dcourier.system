<?php

class BookingController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($shipment_id='')
	{
		$model = new Booking;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$awb = '';
		if(is_numeric($shipment_id))
		{
			$shipment = Shipment::model()->findByPk($shipment_id);
			if($shipment instanceof Shipment)
			{
				$model->address = $shipment->shipper_address;
				$model->city = $shipment->shipper_city;
				$model->postal = $shipment->shipper_postal;
				$model->country = $shipment->shipper_country;
				$model->phone = $shipment->shipper_phone;
				$model->shipment_id = $shipment->id;
				$awb = $shipment->awb;
			}
		}

		if (isset($_POST['Booking']))
		{
			$model->attributes = $_POST['Booking'];
			$model->setAttribute('booking_code', dechex(time()));
			if ($model->save())
			{
				if(!empty($model->shipment_id) || $model->shipment_id != '')
				{
					$shipment->booking_id = $model->id;
					$tes = $shipment->update();
				}
				Yii::app()->user->setFlash('success', 'Success to add new booking, '.$model->booking_code);
				$this->redirect(array('index'));
			}
		}

		$this->render('create', array(
			'model' => $model,
			'awb' => $awb
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Booking']))
		{
			$model->attributes = $_POST['Booking'];
			if ($model->save())
				$this->redirect(array('index'));
		}
		$model->pickup_date = date('m/d/Y', strtotime($model->pickup_date));

		$this->render('update', array(
			'model' => $model,
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
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model = new Booking('search');
		$model->unsetAttributes();	// clear any default values
		if (isset($_GET['Booking']))
			$model->attributes = $_GET['Booking'];

		$this->render('admin', array(
			'model' => $model,
		));
	}
	
	public function actionAssignCourier($id)
	{
		$model = $this->loadModel($id);
		if (isset($_POST['Booking']))
		{
			$model->attributes = $_POST['Booking'];
			if ($model->save())
				$this->redirect(array('index'));
		}
		$criteria = new CDbCriteria;
		$criteria->with = array('user','booking');
		$driver_list_available = new CActiveDataProvider('Driver', array(
			'criteria' => $criteria
		));
		
		if(isset($_GET['driver_id']))
		{
			$model->driver_id=$_GET['driver_id'];
			if($model->save())
			{
				$this->redirect(array('index'));
			}		
		}

		$this->render('assign', array(
			'model'=>$model,
			'driver_list_available'=>$driver_list_available
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = Booking::model()->findByPk($id);
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
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'booking-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionSuggestCustomer()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			if (isset($_GET['mode']) && isset($_GET['term']))
			{
				$mode = $_GET['mode'];
				$customers = Customer::model()->suggest($_GET['term'],$mode);
				$this->setJsonHeader();
				echo CJSON::encode($customers);
				Yii::app()->end();
			}
		}
	}
}
