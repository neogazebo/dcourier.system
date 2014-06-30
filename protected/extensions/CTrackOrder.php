<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CTrackOrder
 *
 * @author cahyo
 */
class CTrackOrder extends CComponent
{

	public function init()
	{
		
	}
	
	/**
	 *
	 * @param CActiveRecord $model
	 * @param int $id
	 * @param array $config 
	 */
	public function registerAssignScript($model, $id, array $config=array())
	{

		Yii::app()->controller->beginWidget('zii.widgets.jui.CJuiDialog', array(
			'id' => 'assignDriver',
			"options" => array(
				'title' => 'Assign Driver',
				'autoOpen' => false,
				'modal' => true,
				'buttons' => array(
					'Close' => 'js:function(){$(this).dialog("close")}',
					'Assign' => 'js:function(){' . CHtml::ajax(array(
						'url' => $config['ajaxUrl'],
						'type' => 'post',
						'dataType' => 'json',
						'data' => 'js:$("#assignDriver form").serialize()',
						'success' => 'function(r){if(r.success){
					$("#assignDriver").dialog("close");
					$.fn.yiiGridView.update("tracking-grid");
				}; 
				if(!r.success){alert("Gagal assign driver")}}'
							)
					) . ';}',
				),
			),
				), true);

		$driverForm = new CForm(array(
					'elements' => array(
						'driver' => array(
							'type' => 'dropdownlist',
							'items' => OrderTracking::getDriverList()
						),
						'id' => array(
							'type' => 'hidden',
						),
					)
						), $model);

		echo $driverForm->render();


		Yii::app()->controller->endWidget();
		Yii::app()->clientScript->registerScript($id, "jQuery('body').undelegate('.assignDriver_button','click').delegate('.assignDriver_button','click',function(){
driverDialog=jQuery('#assignDriver');
driverDialog.dialog('open');
jQuery('#assignDriver input#OrderTracking_id').val($(this).attr('rel').replace('grid.',''));
	return false;
});	");
	}

	/**
	 *
	 * @param ViewTracking $data object yang mau di view
	 * @param string $attribute attribute nya
	 * @param array $config konfigurasi warna dari attribute, misalnya array("red"=>"red") berarti red didapat dari $data->red, isi dengan 'red', 'green', 'yellow', dan 'createdTime' Key dengan benar
	 * @return string label yang sudah berwarna tergantung dari waktu berubah warnanya.
	 */
	public function getStatusLabel(ViewTracking $data, $attribute, array $config)
	{
		$status = 'row-response-msg ';
		$toRed = strtotime("+{$data->$config['red']} minute", $data->$config['createdTime']);
		$toGreen = strtotime("+{$data->$config['green']} minute", $data->$config['createdTime']);
		$toYellow = strtotime("+{$data->$config['yellow']} minute", $data->$config['createdTime']);
		switch ($data->$config['createdTime'])
		{
			case $toRed < time():
				$status.= "error";
				break;
			case $toYellow < time():
				$status.= "warning";
				break;
			case $toGreen > time():
				$status.= "success";
				break;
			case $toGreen < time():
				$status.='success';
				break;
		}
		return CHtml::tag('div', array("class" => $status), CHtml::tag('center', array(), $data->$attribute));
	}

	/**
	 * @param ViewTracking $data
	 * @param string $attribute attribute dari data
	 * @return string atau tombol
	 */
	public function getAssignButton($data, $attribute)
	{
		$return = $data->$attribute;
		switch ($data->shipment_type)
		{
			case 'city':
				$return = is_null($data->$attribute) ? CHtml::submitButton('Assign Driver', array(
							"rel" => "grid.$data->primaryKey",
							"class" => "assignDriver_button",
						)) : $data->$attribute;
				break;
			case 'domestic':
				break;
			case 'international':
				break;
			default:
				break;
		}
		return sprintf('<center>%s</center>', $return);
	}
	
	public function getEplasedTimeTill24()
	{
			$timeNow=date('H:i:s',time());
			$timeNowArray=explode(':',$timeNow);
			$count=0;
			$hms=array();
			foreach($timeNowArray as $val)
			{
				$count++;
				if($count==1){
					$hms['hour']=23-$val+$val;
				}
				if($count==2){
					$hms['minute']=59-$val+$val;
				}
				if($count==3){
					$hms['second']=59-$val+$val;
				}
			}
			return implode(':',$hms);
	}
}

?>
