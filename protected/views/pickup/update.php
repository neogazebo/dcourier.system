<?php
$this->breadcrumbs=array(
	'Pickups'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Pickup', 'url'=>array('index')),
	array('label'=>'Create Pickup', 'url'=>array('create')),
	array('label'=>'View Pickup', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Pickup', 'url'=>array('admin')),
);
?>

<h1>Update Pickup <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'driver_list_available'=>$driver_list_available,'shipment_id'=>$shipment_id)); ?>