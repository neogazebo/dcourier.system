<?php

class SiteController extends Controller
{
	
	public function allowedActions()
	{
		return 'login,error,form_login';
	}

	/**
	 * Declares class-based actions.
	 */
	
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha' => array(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page' => array(
				'class' => 'CViewAction',
			),
		);
	}
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		if (Yii::app()->user->isGuest)
		{
			$this->redirect(Yii::app()->createUrl('site/login'));
		}
		else
		{
			$this->redirect(Yii::app()->createUrl('home'));
		}
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if ($error = Yii::app()->errorHandler->error)
		{
			if (Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
		
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$this->layout = '//layouts/login';
		$cs = Yii::app()->clientScript;

		$cs->registerCoreScript('jquery');
		$cs->registerCoreScript('yiiactiveform');
		$cs->registerCoreScript('jquery.ui');
		// display the login form
		$this->render('login');
	}

	/**
	 * function to render form login on login tabs 
	 */
	public function actionform_login()
	{
		$model = new LoginForm;
		$cs = Yii::app()->clientScript;

		if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		$cs->scriptMap['jquery.js'] = false;
		$cs->scriptMap['jquery.yiiactiveform.js'] = false;
		// collect user input data
		if (isset($_POST['LoginForm']))
		{
			$model->attributes = $_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if ($model->validate() && $model->login())
			{
				$redirectUrl = Yii::app()->createUrl('home');
				echo CHtml::script("location.href='$redirectUrl';");
				Yii::app()->end();
			}
		}
		$this->renderPartial('_formlogin', array('model' => $model), false, true);
		Yii::app()->end();
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}