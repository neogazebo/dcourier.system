<?php

class ApiAccesslistController extends Controller
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
	public function actionCreate($customer_id)
	{
		$model=new ApiAccesslist;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ApiAccesslist']))
		{
			$model->attributes=$_POST['ApiAccesslist'];
			if($model->save())
                                $this->redirect(array('admin','customer_id'=>$customer_id));
//				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
                        'customer_id'=>$customer_id,
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

		if(isset($_POST['ApiAccesslist']))
		{
			$model->attributes=$_POST['ApiAccesslist'];
			if($model->save())
				$this->redirect(array('admin','customer_id'=>$model->customer_id));
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
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex($customer_id)
	{
//		$dataProvider=new CActiveDataProvider('ApiAccesslist');
//		$this->render('index',array(
//			'dataProvider'=>$dataProvider,
//                        'customer_id'=>$customer_id,
//		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin($customer_id)
	{
		$criteria=new CDbCriteria;
                $criteria->condition = 'customer_id='.$customer_id;
                $model = new CActiveDataProvider('ApiAccesslist', array(
					'criteria' => $criteria
				));
		$this->render('admin',array(
			'model'=>$model,
                        'customer_id'=>$customer_id,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ApiAccesslist::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='api-accesslist-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
