<?php

class ReportController extends Controller
{

	public function actionExportExcel()
	{
		Yii::import('ext.EExcelView');

		$dataprovider = new CActiveDataProvider(ViewReport::model());
		$this->widget('EExcelView', array(
			'dataProvider' => $dataprovider,
			'grid_mode' => 'export',
			'title' => 'Title',
			'filename' => 'report',
			'stream' => true,
			'exportType' => 'Excel2007',
			'columns' => $_GET['fields']
		));
	}

	public function actionIndex()
	{
		$this->layout = '//layouts/column1';
		$model = new ViewReport('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['Shipment']))
			$model->attributes = $_GET['Shipment'];

		$shipment_report = new Shipment('search');
		$columns = array('id', 'awb');
		$this->render('index', array(
			'model' => $model,
			'shipment_report' => $shipment_report,
			'columns' => ''
		));
	}

	public function actionGetReport()
	{
		if (Yii::app()->request->isAjaxRequest && isset($_GET['field']))
		{
			$criteria = new CDbCriteria;
			
			if(!empty($_GET['customer_id']) && is_numeric($_GET['customer_id']))
				$criteria->compare('customer_id', $_GET['customer_id']);
			
			if((!empty($_GET['start']) && $_GET['start'] != '') && (!empty($_GET['end']) && $_GET['end'] != ''))
				$criteria->addBetweenCondition('created', $_GET['start'], $_GET['end']);
			
			$columns = $_GET['field'];

			$shipment_report = new CActiveDataProvider(ViewReport::model(),array(
				'criteria'=>$criteria
			));
			$this->renderPartial('_report', array(
				'shipment_report' => $shipment_report,
				'columns' => $columns,
			),FALSE,TRUE);
		}
	}
}