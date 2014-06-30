<?php

class ApitestController extends Controller
{

	public function actionAwbrequest()
	{
		echo "AWB request";
	}

	public function actionCheckawb()
	{
		echo "Check AWB";
	}

	public function actionTesRequestPickup()
	{
		$model = new Shipment;
		$model_city = new ShipmentIntracity;
		$model_domestic = new ShipmentDomestic;
		$model_international = new ShipmentInternational;

		$good_types = GoodType::model()->findAll();

		$model->type = 'parcel';
		$model->goods_type = 'B777';

		$model->delivery_instruction = 'Tes Request Pickup API';
		$model->shipper_address = 'Jl. Taman Palem Selatan';
		$model->shipper_city = 'Jakarta';
		$model->shipper_country = 'Indonesia';
		$model->shipper_name = 'Budi Darmawan';
		$model->shipper_phone = '0818334550';
		$model->shipper_province = 'DKI Jakarta';
		$model->shipper_postal = '12010';

		$model->receiver_address = 'Jl. Kesana Kemari';
		$model->receiver_country = 'Indonesia';
		$model->receiver_name = 'Darmawan Budi';
		$model->receiver_phone = '087888838';
//		$model->receiver_city = 'Bandung';
//		$model->receiver_province = 'Jawa Barat';
		$model->listtype;
		$model->shipment_description = 'Tes API';
		$model->receiver_postal = '42252';

		$this->render('create', array(
			'model' => $model,
			'model_city' => $model_city,
			'model_domestic'=> $model_domestic,
			'model_international'=> $model_international,
			'good_types' => $good_types,
		));
	}
	
	public function actionMailTes()
	{
		$message = new YiiMailMessage;
		$message->setBody('tes','text/html');
		$message->setTo('neo_gazebo@yahoo.co.id');
		$message->setSubject('tes');
		$message->setFrom('admin@dcourier.com');
		
		$html2pdf = Yii::app()->ePdf->HTML2PDF();
		$html2pdf->WriteHTML('<p>hehehehe</p>');
		$html2pdf->Output(dirname(Yii::app()->basePath).'/pdf/tes.pdf',EYiiPdf::OUTPUT_TO_FILE);
		
		$message->attach(Swift_Attachment::frompath(dirname(Yii::app()->basePath).'/pdf/tes.pdf'));

		Yii::app()->mail->send($message);

		CVarDumper::dump(YiiMail::log($message),10,true);
	}
	
	public function actionTesInquiry()
	{
		$model = new InquiryForm;
		
		if(isset($_POST['InquiryForm'])){
			$model->setAttributes($_POST['InquiryForm']);
			$model->validate();
		}
		
		$this->render('tesInquiry',array(
			'model' => $model,
		));
	}
}