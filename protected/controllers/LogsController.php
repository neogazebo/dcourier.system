<?php

class LogsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

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
	 * Manages all models.
	 */
	public function actionUser()
	{
		$this->layout='column1';
		$model=new UserLogs('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserLogs']))
			$model->attributes=$_GET['UserLogs'];
		
		$this->render('user',array(
			'model'=>$model,
		));
	}

}
