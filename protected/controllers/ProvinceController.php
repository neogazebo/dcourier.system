<?php

class ProvinceController extends Controller
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
		$criteria->condition = 'province_id=' . $id;

		$district = new CActiveDataProvider(District::model(), array('criteria' => $criteria));
		$this->render('view', array(
				'model' => $this->loadModel($id),
				'district' => $district,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Province;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Province']))
		{
			$model->attributes = $_POST['Province'];
			if ($model->save())
			{
				Yii::app()->user->setFlash('success', "Provinsi sudah ditambah");
				$this->redirect(array('admin', 'id' => $model->id));
			}
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

		if (isset($_POST['Province']))
		{
			$model->attributes = $_POST['Province'];
			if ($model->save())
			{
				Yii::app()->user->setFlash('success', "Provinsi sudah dirubah");
				$this->redirect(array('admin', 'id' => $model->id));
			}
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
		$dataProvider = new CActiveDataProvider('Province');
		$this->render('index', array(
				'dataProvider' => $dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Province('search');
		$model->unsetAttributes();	// clear any default values
		if (isset($_GET['Province']))
			$model->attributes = $_GET['Province'];

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
		$model = Province::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	public function actionAutocomplete()
	{
		$Zone = array();


		if (isset($_GET['term']))
		{
			// http://www.yiiframework.com/doc/guide/database.dao
			$qtxt = "SELECT name FROM Province WHERE name LIKE :name";
			$command = Yii::app()->db->createCommand($qtxt);
			$command->bindValue(":name", '' . $_GET['term'] . '%', PDO::PARAM_STR);
			$res = $command->queryColumn();
		}

		echo CJSON::encode($res);
		//var_dump($res);
		Yii::app()->end();
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'province-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
