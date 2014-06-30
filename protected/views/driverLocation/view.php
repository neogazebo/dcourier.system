<?php
/* @var $this DriverLocationController */
/* @var $model DriverLocation */

$this->breadcrumbs=array(
	'Driver Locations'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List DriverLocation', 'url'=>array('index')),
	array('label'=>'Create DriverLocation', 'url'=>array('create')),
	array('label'=>'Update DriverLocation', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete DriverLocation', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage DriverLocation', 'url'=>array('admin')),
);
?>

<h1>View DriverLocation #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'driver_user_id',
		'time',
		'lat',
		'long',
	),
)); ?>
