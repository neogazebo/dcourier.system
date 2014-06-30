<?php

class UserController extends Controller
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
		if (Yii::app()->request->isAjaxRequest)
		{
//outputProcessing = true because including css-files ...
			$this->renderPartial('view', array(
				'model' => $this->loadModel($id),
					), false, true);
//js-code to open the dialog    
			if (!empty($_GET['asDialog']))
				echo CHtml::script('$("#dialog_id").dialog("open").dialog( { title: "Detail View" } )');
			Yii::app()->end();
		}
		else
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
		$model = new User('create');
		// Uncomment the following line if AJAX validation is needed
		if (isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];
			if ($model->validate())
			{
				if ($model->save())
				{
					if (isset($_POST['UserAssignmentForm']))
					{
						foreach ($_POST['UserAssignmentForm'] as $key => $value)
						{
							if (is_array($value))
							{
								foreach ($value as $data)
								{
									$formModel = new AssignmentForm();
									if ($key == 'role')
										$formModel->itemname = $data;
									if ($formModel->validate() === true)
									{
										$authorizer = Yii::app()->authManager;
										$authorizer->assign($formModel->itemname, $model->primaryKey);
										$item = $authorizer->getAuthItem($formModel->itemname);
									}
								}
							}
						}
					}
					UserLogs::createLog('create new ' . get_class($model) . ' ' . 'id:' . $model->primaryKey . ' name:' . $model->username);
				}
				Yii::app()->user->setFlash('success', 'User sudah ditambah.');
				$this->redirect(array('index'));
			}
		}
		else
		{
			$model->timezone = 'Asia/Jakarta';
		}
		$this->render('create', array(
			'model' => $model,
			'permissionModel' => new UserAssignmentForm(),
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
		$formModel = new UserAssignmentForm();
		$sql = "select * from AuthAssignment where userid = '$id'";
		$dataProvider = new CSqlDataProvider($sql);
		foreach ($dataProvider->getData() as $key => $data)
		{
			$formModel->role[] = $data['itemname'];
		}
		if (isset($_POST['User']))
		{
			$oldname = $model->username;
			$model->attributes = $_POST['User'];
			if ($model->validate())
			{
				if (isset($_FILES['User']['name']['image'])&& is_uploaded_file($_FILES['User']['tmp_name']['image']))
					$model->upload($_FILES['User']);
				$model->save();
				if (isset($_POST['UserAssignmentForm']))
				{
					foreach ($_POST['UserAssignmentForm'] as $key => $value)
					{
						if (is_array($value) && is_array($formModel->role))
						{
							$toRevoke = array_diff($formModel->role, $value);
							foreach ($toRevoke as $revokeData)
							{
								Yii::app()->authManager->revoke($revokeData, $id);
							}
						}
						if (!empty($value))
						{
							foreach ($value as $data)
							{
								$formModel->save($data, $id, $key);
							}
						}
					}
				}
				UserLogs::createLog('update ' . get_class($model) . ' ' . 'id:' . $model->primaryKey . ' old name:' . $oldname . ', name:' . $model->username);

				Yii::app()->user->setFlash('success', 'Informasi User sudah dirubah.');
				$this->redirect(array('index'));
			}
		}
		$this->render('update', array('model' => $model,
			'permissionModel' => $formModel,
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
			$model = $this->loadModel($id);
			UserLogs::createLog('delete ' . get_class($model) . ' ' . 'id:' . $model->primaryKey . ' name:' . $model->username);

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
	 * Manages all models.
	 */
	public function actionIndex()
	{
		if (Yii::app()->user->isGuest)
			$this->redirect(Yii::app()->createUrl('site/login'));
		$model = new User('search');
		$model->unsetAttributes(); // clear any default values
		$cs = Yii::app()->clientScript;
//$cs->scriptMap['jquery-ui.css'] = false;
		$cs->registerCoreScript('jquery');
		$cs->registerCoreScript('yiiactiveform');
		if (isset($_GET['User']))
			$model->attributes = $_GET['User'];

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
		$model = User::model()->findByPk($id);
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
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}