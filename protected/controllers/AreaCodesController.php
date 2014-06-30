<?php

class AreaCodesController extends Controller
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
	public function actionCreate()
	{
		$model=new AreaCodes;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AreaCodes']))
		{
			$model->attributes=$_POST['AreaCodes'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->code));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AreaCodes']))
		{
			$model->attributes=$_POST['AreaCodes'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->code));
		}

		$this->render('update',array(
			'model'=>$model,
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
		$dataProvider=new CActiveDataProvider('AreaCodes');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new AreaCodes('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AreaCodes']))
			$model->attributes=$_GET['AreaCodes'];

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
		$model=AreaCodes::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='area-codes-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionImportCSV()
	{
		ini_set('max_execution_time', 300);
		$this->layout = '//layouts/column2';
		$model = new FAreaCodesCSV;
		
		$code_area = '';
		$city = '';
		$main_city_code = '';
		$location = '';

		if (isset($_POST['FAreaCodesCSV']))
		{

			$model->attributes = $_POST['FAreaCodesCSV'];

			if ($model->validate())
			{

				$csvFile = CUploadedFile::getInstance($model, 'file');
				$tempLoc = $csvFile->getTempName();
				$rawdatas = file($tempLoc);
				try
				{
					$connection = Yii::app()->db;
					$transaction = $connection->beginTransaction();
					$sql = "INSERT INTO area_code (code, city, main_city_code, location) VALUES(:code, :city, :main_city_code, :location)";
					$command = $connection->createCommand($sql);
					$command->bindParam(":code", $code_area);
					$command->bindParam(":city", $city);
					$command->bindParam(':main_city_code', $main_city_code);
					$command->bindParam(':location', $location);

					foreach ($rawdatas as $codes)
					{
						$code = explode(',', $codes);
						
						for($i = 0;$i < 4;$i++) $code[$i] = trim($code[$i]);
						
						$code_area = $code[0];
						$city = $code[1];
						$main_city_code = $code[2];
						$location = $code[3];

						$exec = $command->execute();
					}
					$transaction->commit();
				}
				catch (Exception $e) // an exception is raised if a query fails
				{
					CVarDumper::dump($e, 10, TRUE);
					exit;
					$transaction->rollBack();
				}
			}
		}

		$this->render("importcsv", array('model' => $model));
	}
}
