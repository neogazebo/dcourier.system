<?php

class RateCompanyServiceController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($rate_company_id)
	{
		$model = new RateCompanyService;
		$model->rate_company_id = $rate_company_id;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['RateCompanyService']))
		{
			$model->attributes = $_POST['RateCompanyService'];

			if ($model->save())
				$this->redirect(array('index', 'rate_company_id' => $model->rate_company_id));
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

		if (isset($_POST['RateCompanyService']))
		{
			$model->attributes = $_POST['RateCompanyService'];

			if ($model->save())
				$this->redirect(array('index', 'rate_company_id' => $model->rate_company_id));
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
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex($rate_company_id)
	{
		$model = new RateCompanyService;
		$model->rate_company_id = $rate_company_id;
		if (isset($_GET['RateCompanyService']))
			$model->attributes = $_GET['RateCompanyService'];

		$this->render('admin', array(
			'model' => $model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new RateCompanyService('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['RateCompanyService']))
			$model->attributes = $_GET['RateCompanyService'];

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
		$model = RateCompanyService::model()->findByPk($id);
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
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'rate-company-service-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
//        public function getProductService($id)
//        {
//            $product = self::model()->findAll('product_id=:product_id', array('product_id'=>$id));
//            $productArray = CHtml::listData($product, 'product_id', 'name');  
//            return $cityArray;
//        }
}
