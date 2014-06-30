<?php

class ZoneController extends Controller
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
		$criteria = new CDbCriteria;
		$criteria->condition = 'zone_id=' . $id;
		$area = new CActiveDataProvider(Area::model(), array('criteria' => $criteria));
		$this->render('view', array(
				'model' => $this->loadModel($id),
				'area' => $area,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($id)
	{
		$model = new Zone;
		$model->district_id = $id;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Zone']))
		{
			$model->attributes = $_POST['Zone'];
			if ($model->save())
				$this->redirect(array('district/view', 'id' => $model->district_id));
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

		if (isset($_POST['Zone']))
		{
			$model->attributes = $_POST['Zone'];
			if ($model->save())
				$this->redirect(Yii::app()->createUrl('district/view', array('id' => $model->district_id)));
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
		$dataProvider = new CActiveDataProvider('Zone');
		$this->render('index', array(
				'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Zone('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['Zone']))
			$model->attributes = $_GET['Zone'];

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
		$model = Zone::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	public function actionAutocompleteZone()
	{
		$Zone = array();

//		if (isset($_GET['term']))
//		{
//			$criteria = new CDbCriteria;
//			$criteria->select = array('name');
//			$criteria->condition = "name LIKE :sterm";
//			$criteria->params = array(":sterm" => "%" .$_GET['term'] . "%");
//			$Zone = Zone::model()->findAll($criteria);
//		}
		if (isset($_GET['term']))
		{
			// http://www.yiiframework.com/doc/guide/database.dao
			$qtxt = "SELECT name FROM zone WHERE name LIKE :name";
			$command = Yii::app()->db->createCommand($qtxt);
			$command->bindValue(":name", '' . $_GET['term'] . '%', PDO::PARAM_STR);
			$res = $command->queryColumn();
		}

		echo CJSON::encode($res);
		Yii::app()->end();
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'zone-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
