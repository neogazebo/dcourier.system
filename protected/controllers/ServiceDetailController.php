<?php

class ServiceDetailController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($company_service_id)
	{
		$model=new ServiceDetail;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$availableProductServices = ProductService::getAvailableService($company_service_id);
		
		$model->rate_company_service_id = $company_service_id;
		if($model->rateCompanyService)
		
		if(isset($_POST['ServiceDetail']))
		{
			$model->attributes=$_POST['ServiceDetail'];
			if($model->save())
				$this->redirect(array('index','company_service_id'=>$company_service_id));
		}

		$this->render('create',array(
			'model'=>$model,
			'availableProductServices'=>$availableProductServices,
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
		
		$availableProductServices = ProductService::getAvailableService($model->rate_company_service_id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ServiceDetail']))
		{
			$model->attributes=$_POST['ServiceDetail'];
			if($model->save())
				$this->redirect(array('index','company_service_id'=>$model->rate_company_service_id));
		}

		$this->render('update',array(
			'model'=>$model,
			'availableProductServices'=>$availableProductServices,
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
	 * Manages all models.
	 */
	public function actionIndex($company_service_id)
	{
		$model=new ServiceDetail('search');
		$model->unsetAttributes();  // clear any default values
		$model->rate_company_service_id = $company_service_id;
		
		if(isset($_GET['ServiceDetail']))
			$model->attributes=$_GET['ServiceDetail'];

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
		$model=ServiceDetail::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='service-detail-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
