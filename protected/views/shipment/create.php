<?php
$this->breadcrumbs=array(
	'Shipments'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Shipment', 'url'=>array('admin')),
);

$data_render = array('model'=>$model, 'items'=>$items, 'costs'=>$costs, 'goods_type'=>  CHtml::listData($good_types, 'code', 'desc'));

if($service_type == 'domestic')
	$data_render['model_domestic'] = $model_domestic;
else if($service_type == 'city')
	$data_render['model_city'] = $model_city;
else if($service_type == 'international')
	$data_render['model_international'] = $model_international;

?>

<h4 class="ui-box-header ui-corner-all">
		Create <?php echo ucwords($service_type); ?> Shipment 
</h4>

<?php echo $this->renderPartial('_form_'.$service_type, $data_render); 
?>
