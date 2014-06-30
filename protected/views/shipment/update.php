<?php
$this->breadcrumbs=array(
	'Shipments'=>array('admin'),
	'Update',
);

$this->menu=array(
	array('label'=>'Manage Shipment', 'url'=>array('admin')),
);
?>

<h4 class="ui-box-header ui-corner-all">
		Update <?php echo ucwords($service_type); ?> Shipment 
</h4>

<?php
	if($service_type == 'domestic')
		$dataRander = array('model'=>$model, 'items'=>$items, 'costs'=>$costs,'model_domestic' =>$model_domestic, 'goods_type'=>  CHtml::listData($good_types, 'code', 'desc'));
	else if($service_type == 'city')
		$dataRander = array('model'=>$model, 'items'=>$items, 'costs'=>$costs,'model_city' => $model_city, 'goods_type'=>  CHtml::listData($good_types, 'code', 'desc'));
	else if($service_type == 'international')
		$dataRander = array('model'=>$model, 'items'=>$items, 'costs'=>$costs, 'model_international' => $model_international,'goods_type'=>  CHtml::listData($good_types, 'code', 'desc'));
	
	echo $this->renderPartial('_form_'.$service_type, $dataRander); 
?>