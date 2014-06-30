<?php
$this->breadcrumbs=array(
	'Pickups'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Pickup', 'url'=>array('index')),
	array('label'=>'Manage Pickup', 'url'=>array('admin')),
);
?>

<h1>Create Pickup</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'driver_list_available'=>$driver_list_available,'shipment_id'=>$shipment_id)); ?>