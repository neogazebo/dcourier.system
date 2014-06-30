<?php

class InvoicesController extends Controller
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
		$model = $this->loadModel($id);
		$criteria = new CDbCriteria;
		$criteria->compare('invoice_id', $id);
		
		$invoice_transaction = new CActiveDataProvider('Transaction',array(
			'criteria' => $criteria
		));
		
		$total_amount = 0;
		foreach($invoice_transaction->getData() as $key)
		{
			$total_amount = $total_amount + $key->total; 
		}
		
		$criteriaCustomer = new CDbCriteria;
		$criteriaCustomer->compare('id',$model->customer_id);
		
		$customer = new CActiveDataProvider('Customer',array(
			'criteria' => $criteriaCustomer
		));
		
		$this->render('view',array(
			'customer' => $customer,
			'model'=>$model,
			'invoice_transaction' => $invoice_transaction,
			'total_amount' => $total_amount,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($id)
	{
		$model=new Invoices;
		$formInvoice = new InvoiceForm;
		$customer = Customer::model()->findByPk($id);
		
		$criteria = new CDbCriteria;
		$criteria->compare('customer_id', $id);
		$criteria->condition = 'invoice_id is null';
		
		$cust_transaction = new CActiveDataProvider('Transaction',array(
			'criteria' => $criteria
		));
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['InvoiceForm']))
		{
			$formInvoice->attributes = $_POST['InvoiceForm'];
			
			$model->customer_id = $customer->id;
			try
			{
				
				if($formInvoice->validate())
				{
					if($model->save())
					{
						$db=Yii::app()->db;
						if($formInvoice->method == 'all')
						{
							$trans = Yii::app()->db->beginTransaction();
							$cmk=$db->createCommand('SELECT * FROM transaction WHERE customer_id = :customer_id and invoice_id is null for update');
							$cmk->bindValue(':customer_id',$model->customer_id,PDO::PARAM_INT);
							Transaction::model()->updateAll(array('invoice_id'=>$model->id),'customer_id = :customer_id and invoice_id is null',array(':customer_id'=>$customer->id));
							$trans->commit();
						}
						else if($formInvoice->method == 'not_all')
						{
							$trans = Yii::app()->db->beginTransaction();
							$cmk=$db->createCommand('SELECT * FROM transaction WHERE customer_id = :customer_id and invoice_id is null and MONTH(FROM_UNIXTIME(created)) = :month for update');
							$cmk->bindValue(':month',$formInvoice->trans_month,PDO::PARAM_INT);
							$cmk->bindValue(':customer_id',$model->customer_id,PDO::PARAM_INT);
							
							Transaction::model()->updateAll(array('invoice_id'=>$model->id),'customer_id = :customer_id and MONTH(FROM_UNIXTIME(created)) = :month and invoice_id is null',array(':customer_id'=>$customer->id,':month' => $formInvoice->trans_month));
							$trans->commit();
						}
						else if($formInvoice->method == 'custom')
						{
							$trans = Yii::app()->db->beginTransaction();
							foreach ($formInvoice->trans_id as $key => $val)
							{
								$cmk=$db->createCommand('SELECT * FROM transaction WHERE customer_id = :customer_id and invoice_id is null and id = :trans_id for update');
								$cmk->bindValue(':customer_id',$model->customer_id,PDO::PARAM_INT);
								$cmk->bindValue(':trans_id',$val,PDO::PARAM_INT);
								Transaction::model()->updateByPk($val,array('invoice_id'=>$model->id),'customer_id = :customer_id and invoice_id is null',array(':customer_id'=>$customer->id));
							}
							$trans->commit();
						}
						
						$cmd=$db->createCommand('SELECT SUM(total) FROM transaction WHERE invoice_id = :invoice_id');
						$cmd->bindValue(':invoice_id',$model->id,PDO::PARAM_INT);
						$model->total=$cmd->queryScalar();
						$model->save(false);
						UserLogs::createLog('create new Invoice '.$model->id ,'info',get_class($model));
						$this->redirect(array('viewInvoice','id'=>$customer->id));
					}
				}
				
			}
			catch (CDbException $e)
			{
				$trans->rollback();
				throw $e;
			}
		}
		
		$this->render('create',array(
			'model'=>$model,
			'formInvoice' => $formInvoice,
			'customer' => $customer,
			'cust_transaction' => $cust_transaction
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
		$customer = Customer::model()->findByPk($model->customer_id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Invoices']))
		{
			$model->attributes=$_POST['Invoices'];
			if($model->save())
			{
				UserLogs::createLog('update Invoice '.$model->id ,'info',get_class($model));
				$this->redirect(array('viewInvoice','id'=>$model->customer_id));
			}
		}
		if($model->tgl_pembayaran != '')
			$model->tgl_pembayaran = date('m/d/Y', strtotime($model->tgl_pembayaran));

		$this->render('update',array(
			'model'=>$model,
			'customer'=>$customer
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
			UserLogs::createLog('delete Invoice '.$id ,'info','Invoice');

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * View Invoice Customer.
	 */
	public function actionViewInvoice($id)
	{
		$customer = Customer::model()->findByPk($id);
 		$criteria=new CDbCriteria;
		$criteria->compare('customer_id',$id);

		$dataProvider = new CActiveDataProvider('Invoices', array(
			'criteria'=>$criteria,
		));
		
		$this->render('customer_invoice',array(
			'dataProvider'=>$dataProvider,
			'customer' => $customer
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Invoices('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Invoices']))
			$model->attributes=$_GET['Invoices'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionGenerate($id)
	{
		$html2pdf = Yii::app()->ePdf->HTML2PDF();
		
		$model = $this->loadModel($id);
		
		$invoice_transaction = Transaction::model()->findAllByAttributes(array('invoice_id' => $id));
		
		$total_amount = 0;
		foreach($invoice_transaction as $key)
		{
			$total_amount = $total_amount + $key->total; 
		}
		
		$customer = Customer::model()->findByPk($model->customer_id);
		
		$html2pdf->WriteHTML($this->renderPartial('pdf_invoice', array(
			'customer' => $customer,
			'model'=>$model,
			'invoice_transaction' => $invoice_transaction,
			'total_amount' => $total_amount,
		), true));
		$html2pdf->Output('document.pdf',EYiiPdf::OUTPUT_TO_BROWSER);
		
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Invoices::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='invoices-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionCustomerList()
	{
		$model = new Customer('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['Customer']))
			$model->attributes = $_GET['Customer'];

		$this->render('customerList', array(
			'model' => $model,
		));
	}
}
