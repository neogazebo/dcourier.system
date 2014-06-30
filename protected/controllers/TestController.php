<?php

class TestController extends CController
{

	public function actionIndex()
	{
		$html2pdf = Yii::app()->ePdf->HTML2PDF();
		$html2pdf->WriteHTML($this->renderPartial('/shipment/tes', array(), true));
		$html2pdf->Output(dirname(Yii::app()->basePath) . '/pdf/tes2.pdf', EYiiPdf::OUTPUT_TO_BROWSER);
	}

	public function actionTesExcel()
	{
		Yii::import('ext.EExcelView');


		$dataprovider = new CActiveDataProvider('Shipment');
		$this->widget('EExcelView', array(
			'dataProvider' => $dataprovider,
			'grid_mode' => 'export',
			'title' => 'Title',
			'filename' => 'report',
			'stream' => true,
			'exportType' => 'Excel2007',

		));
	}
}