<?php

class ProfileController extends Controller
{
	public function actionIndex()
	{
		$model=  UserProfile::model()->findByPk(Yii::app()->user->id);
		if(isset($_POST['UserProfile'])){
				$model->attributes=$_POST['UserProfile'];
				if($model->validate()){
					if (isset($_FILES['UserProfile']['name']['image'])&& is_uploaded_file($_FILES['UserProfile']['tmp_name']['image']))
						$model->upload($_FILES['UserProfile']);
					$model->save();
						Yii::app()->user->setFlash('updateProfile','Info anda berhasil dirubah.');
				}
		}
		$this->render('index',array('model'=>$model));
	}

}