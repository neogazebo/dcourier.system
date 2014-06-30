<?php
$this->breadcrumbs=array(
	'Shipments'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Shipment', 'url'=>array('admin')),
);
?>

<h4 class="ui-box-header ui-corner-all">
		Create <?php echo ucwords($service_type); ?> Shipment 
</h4>

<?php echo $this->renderPartial('_form', array('service_type'=>$service_type,'model'=>$model, 'items'=>$items, 'costs'=>$costs, 'goods_type'=>  CHtml::listData($good_types, 'code', 'desc'))); 
?>
