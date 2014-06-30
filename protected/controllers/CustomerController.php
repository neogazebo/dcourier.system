<?php

class CustomerController extends Controller
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
	public function actionCreate()
	{
		$model = new Customer;
		$contact = new Contact;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation(array($model, $contact));
		if ($model->save(false))
			$this->redirect(array('update', 'id' => $model->id));

		$this->render('create', array(
			'model' => $model,
			'contact' => $contact,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$criteria = new CDbCriteria;
		$criteria->condition = 'customer_id=' . $id;
		$model = $this->loadModel($id);
		$model->is_allow_api = 1;
		$contact = Contact::model()->find('parent_model=:pm and parent_id=:pid', array(':pm' => 'Customer', ':pid' => $id));
		if (!($contact instanceof Contact))
			$contact = new Contact;
		$rekening = new CActiveDataProvider('CustomerRekening', array(
					'criteria' => $criteria
				));
		$customer_contacts = new CArrayDataProvider($model->contacts, array(
					'id' => 'contact',
					'pagination' => array(
						'pageSize' => 10,
					),
				));
		$customer_tokens = new CActiveDataProvider('Token', array(
					'criteria' => $criteria
				));

		$customer_shipping_profile = new CActiveDataProvider('CustomerShippingProfile', array(
					'criteria' => $criteria
				));
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Customer']) && isset($_POST['Contact']))
		{
			$trans = Yii::app()->db->beginTransaction();
			try
			{
				$model->attributes = $_POST['Customer'];
				$contact->attributes = $_POST['Contact'];
				if ($model->save())
				{
					$contact->parent_id = $model->id;
					$contact->parent_model = 'Customer';
					if ($contact->save())
					{
						UserLogs::createLog('create new ' . get_class($model) . ' ' . $model->id);
						Yii::app()->user->setFlash('success', 'Customer ' . $model->name . '\'s information has been updated');
						$trans->commit();
						$this->redirect(array('view', 'id' => $model->id));
					}
					else
					{
						throw new CException(var_export($contact->getErrors(),true));
					}
				}
			}
			catch (CException $e)
			{
				$trans->rollback();
				throw $e;
			}
		}

		$contact->dob = date('m/d/Y', strtotime($contact->dob));

		$this->render('update', array(
			'model' => $model,
			'contact' => $contact,
			'customer_contacs' => $customer_contacts,
			'rekening' => $rekening,
			'customer_tokens' => $customer_tokens,
			'customer_shipping_profile' => $customer_shipping_profile
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
			$this->layout = '//layouts/column1';
			try
			{
				$model = $this->loadModel($id);
				// we only allow deletion via POST request
				UserLogs::createLog('Delete ' . get_class($model) . ' ' . "accountnr=$model->accountnr, name: $model->name");
				$this->loadModel($id)->delete();
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			}
			catch (CDbException $e)
			{
				$this->render('delete-error');
			}
		}
		else
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Customer('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['Customer']))
			$model->attributes = $_GET['Customer'];

		$this->render('admin', array(
			'model' => $model,
		));
	}

	public function actionContact($id)
	{
		$customer = Customer::model()->findByPk($id);
		$customer_contacts = new CArrayDataProvider($customer->contacts, array(
					'id' => 'contact',
					'pagination' => array(
						'pageSize' => 10,
					),
				));
		$this->render('contacts', array(
			'customer_contacts' => $customer_contacts,
			'id' => $id,
			'name' => $customer->name,
		));
	}

	public function actionCreateContact($id)
	{
		$customerContact = new CustomerContact();
		$contact = new Contact();
		$customer = Customer::model()->findByPk($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation(array($customerContact));
		if (isset($_POST['Contact']) && isset($_POST['CustomerContact']))
		{
			$trans = Yii::app()->db->beginTransaction();
			try
			{
				$contact->attributes = $_POST['Contact'];
				$customerContact->attributes = $_POST['CustomerContact'];

				if ($customerContact->save())
				{
					$contact->parent_model = 'CustomerContact';
					$contact->parent_id = $customerContact->id;
					if ($contact->save())
					{
						$trans->commit();
						UserLogs::createLog('create new ' . get_class($contact) . ' ' . $contact->primaryKey);
						Yii::app()->user->setFlash('success', 'Contact Customer sudah ditambah.');
						$this->redirect(array('customer/update', 'id' => $id));
					}
					else
						throw new CustomerControllerException($contact->getErrors());
				}
				else
					throw new CustomerControllerException($customerContact->getErrors());
			}
			catch (CustomerControllerException $e)
			{
				$trans->rollback();
			}
			catch (CDbException $e)
			{
				$trans->rollback();
			}
		}

		$customerContact->customer_id = $id;

		$this->render('create_contact', array(
			'contact' => $contact,
			'customerContact' => $customerContact,
			'id' => $id,
			'customer_name' => $customer->name,
		));
	}

	public function actionCreateRekening($id)
	{
		$rekening = new CustomerRekening;
		$customer = Customer::model()->findByPk($id);

		$this->performAjaxValidation(array($rekening));
		if (isset($_POST['CustomerRekening']))
		{
			$rekening->attributes = $_POST['CustomerRekening'];
			if ($rekening->save())
			{
				Yii::app()->user->setFlash('success', 'Contact Customer sudah ditambah.');
				$this->redirect(array('customer/update', 'id' => $id));
			}
		}

		$this->render('create_rekening', array(
			'rekening' => $rekening,
			'id' => $id,
			'customer' => $customer,
		));
	}

	public function actionUpdateRekening($id)
	{
		$rekening = CustomerRekening::model()->findByPk($id);
		$customer = Customer::model()->findByPk($rekening->customer_id);

		$this->performAjaxValidation(array($rekening));
		if (isset($_POST['CustomerRekening']))
		{
			$rekening->attributes = $_POST['CustomerRekening'];
			if ($rekening->save())
			{
				Yii::app()->user->setFlash('success', 'Contact Customer sudah ditambah.');
				$this->redirect(array('customer/update', 'id' => $rekening->customer_id));
			}
		}

		$this->render('update_rekening', array(
			'rekening' => $rekening,
			'customer' => $customer,
		));
	}

	public function actionDeleteRekening($id)
	{
		if (Yii::app()->request->isPostRequest)
		{
			$this->layout = '//layouts/column1';
			try
			{
				$rekening = CustomerRekening::model()->findByPk($id);
				$customer = Customer::model()->findByPk($rekening->customer_id);
				// we only allow deletion via POST request

				$rekening->delete();
				if (!isset($_GET['ajax']))
					$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : Yii::app()->createUrl("/customer/update", array("id" => $customer->id)));
			}
			catch (CDbException $e)
			{
				$this->render('delete-error');
			}
		}
		else
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
	}

	public function actionDeleteContact($id)
	{
//		echo $id;exit;
		if (Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$contact = Contact::model()->findByPk($id);
			$customer_contact = CustomerContact::model()->findByPk($contact->parent_id);
			$custid = $customer_contact->customer_id;
			$trans = Yii::app()->db->beginTransaction();

			if (!$customer_contact->delete())
			{
				$trans->rollback();
				throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
			}

			if (!$contact->delete())
			{
				$trans->rollback();
				throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
			}

			$trans->commit();
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : Yii::app()->createUrl("/customer/update", array("id" => $custid)));
		}
		else
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
	}

	public function actionUpdateContact($id)
	{
		$contact = Contact::model()->findByPk($id);
		$customerContact = CustomerContact::model()->findByPk($contact->parent_id);
		$customer = Customer::model()->findByPk($customerContact->customer_id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation(array($customerContact));
		if (isset($_POST['Contact']) && isset($_POST['CustomerContact']))
		{
			$trans = Yii::app()->db->beginTransaction();
			try
			{
				$contact->attributes = $_POST['Contact'];
				$customerContact->attributes = $_POST['CustomerContact'];

				if ($customerContact->save())
				{
					$contact->parent_model = 'CustomerContact';
					$contact->parent_id = $customerContact->id;
					if ($contact->save())
					{
						$trans->commit();
						Yii::app()->user->setFlash('success', 'Contact Customer sudah dirubah.');
						$this->redirect(array('customer/update', 'id' => $customerContact->customer_id));
					}
					else
						throw new CustomerControllerException($contact->getErrors());
				}
				else
					throw new CustomerControllerException($customerContact->getErrors());
			}
			catch (CustomerControllerException $e)
			{
				$trans->rollback();
			}
			catch (CDbException $e)
			{
				$trans->rollback();
			}
		}

		$this->render('update_contact', array(
			'contact' => $contact,
			'customerContact' => $customerContact,
			'id' => $id,
			'customer_name' => $customer->name,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		$new_comment = new CustomerComment;
		$comments = CustomerComment::model()->findAllByAttributes(array('customer_id' => $id));
		$criteria = new CDbCriteria;
		$criteria->condition = 'customer_id=' . $id;
		$service_detail = ServiceDetail::model();

		$contact_list = new CArrayDataProvider($model->contacts, array(
					'id' => 'contact',
				));
		$shipment_log = new CActiveDataProvider('Shipment', array(
					'criteria' => $criteria
				));

		$this->render('view', array(
			'model' => $model,
			'shipment_log' => $shipment_log,
			'comments' => $comments,
			'contact_list' => $contact_list,
			'new_comment' => $new_comment,
			'service_detail' => $service_detail
		));
	}

	public function actionGenerateAcountNumber($id)
	{
		$customer = $this->loadModel($id);
		$accnumber = $customer->generateAccountNumber();
		if (!(!$accnumber))
		{
			$customer->accountnr = $accnumber;
			$customer->save();
		}
		$this->redirect(array('view', 'id' => $id));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = Customer::model()->findByPk($id);
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
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'customer-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionDiscount($id)
	{
		$customer = $this->loadModel($id);

		$services = new ServiceDetail('search');
		$services->unsetAttributes();	// clear any default values
		if (isset($_GET['ServiceDetail']))
			$services->attributes = $_GET['ServiceDetail'];
		
		if (isset($_POST['CustomerDiscount']))
		{
			foreach ($_POST['CustomerDiscount'] as $key => $data)
			{
				if (isset($data['id']))
					$model = CustomerDiscount::model()->findByPk($data['id']);
				else
					$model = new CustomerDiscount;

				$model->setAttributes($data);

				$model->save();
			}
		}

		$this->render('discount', array(
			'services' => $services,
			'customer' => $customer,
		));
	}

	public function actionCreateComments($id)
	{
		if (isset($_POST['CustomerComment']))
		{
			$comment = new CustomerComment;
			$comment->attributes = $_POST['CustomerComment'];
			$comment->customer_id = $id;
			$comment->user_id = Yii::app()->user->id;
			$comment->save();
			$model = CustomerComment::model()->findByPk($comment->id);
			$this->renderPartial('_comment', array(
				'comment' => $model,
			));
		}
	}

	public function actionCreateToken($cust_id)
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			if (isset($cust_id))
			{
				$customer = Customer::model()->findByPk($cust_id);
				$new_token = new Token;
				$new_token->customer_id = $customer->id;
				$new_token->token = Token::create($customer->id);
				$new_token->created = time();

				$new_token->save();

				$criteria = new CDbCriteria;
				$criteria->condition = 'customer_id=' . $customer->id;
				;

				$customer_tokens = new CActiveDataProvider('Token', array(
							'criteria' => $criteria
						));

				$this->renderPartial('_tokens', array(
					'customer_tokens' => $customer_tokens
				));
			}
		}
	}

	public function actionCreateShippingProfile($cid)
	{
		$customer_shipping_profile = new CustomerShippingProfile;
		if (isset($_POST['CustomerShippingProfile']))
		{
			$customer_shipping_profile->setAttributes($_POST['CustomerShippingProfile']);
			$customer_shipping_profile->customer_id = $cid;
			if ($customer_shipping_profile->save())
			{
				$this->redirect(array('update', 'id' => $cid));
			}
		}
		$this->render('create_shipping_profile', array(
			'customer_shipping_profile' => $customer_shipping_profile
		));
	}

	public function actionGetProductServiceList()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			if (isset($_POST['product_id']) && is_numeric($_POST['product_id']))
			{
				$customer_shipping_profile = new CustomerShippingProfile;
				$this->renderPartial('_drop_down_product_service', array(
					'product_id' => $_POST['product_id'],
					'customer_shipping_profile' => $customer_shipping_profile,
				));
			}
		}
	}
}

class CustomerControllerException extends CException
{
	public $errors;

	public function __construct($errors)
	{
		$this->errors = $errors;
	}
}
