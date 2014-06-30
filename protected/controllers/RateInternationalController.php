<?php

class RateInternationalController extends Controller
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
	public function actionCreate()
	{
		$model = new RateInternational;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['RateInternational']))
		{
			$model->attributes = $_POST['RateInternational'];
			if ($model->save())
				$this->redirect(array('index'));
		}

		$this->render('create', array(
			'model' => $model,
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

		if (isset($_POST['RateInternational']))
		{
			$model->attributes = $_POST['RateInternational'];
			if ($model->save())
				$this->redirect(array('index'));
		}

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
		$model = new RateInternational('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['RateInternational']))
			$model->attributes = $_GET['RateInternational'];

		$this->render('admin', array(
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
		$model = RateInternational::model()->findByPk($id);
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
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'international-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionAutoCompleteLookup()
	{
		if (Yii::app()->request->isAjaxRequest && isset($_GET['q']))
		{
			/* q is the default GET variable name that is used by
			  / the autocomplete widget to pass in user input
			 */
			$name = $_GET['q'];
			// this was set with the "max" attribute of the CAutoComplete widget
			$limit = min($_GET['limit'], 50);
			$criteria = new CDbCriteria;
			$criteria->condition = "country LIKE :sterm";
			$criteria->params = array(":sterm" => "%$name%");
			$criteria->limit = $limit;
			$userArray = ZoneInternational::model()->findAll($criteria);
			$returnVal = '';
			foreach ($userArray as $userAccount)
			{
				$returnVal .= $userAccount->getAttribute('country') . '|'
						. $userAccount->getAttribute('zone') . "\n";
			}
			echo $returnVal;
		}
	}

	public function actionGetHarga()
	{
		$zone = $_POST['zone'];
		$weight = $_POST['weight'];
		$package = $_POST['package'];
		$criteria = new CDbCriteria();
		$criteria->condition = 'type = :type and weight = :weight';
		$criteria->params = array(
			':type'=>$package,
			':weight'=>$weight
		);
		$model = RateInternational::model()->find($criteria);
		$harga = $model->$zone;
		$array = array($harga);
		echo json_encode($array);
	}
}
