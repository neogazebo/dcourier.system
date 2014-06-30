<?php
/* @var $this DriverController */
/* @var $model Driver */

$this->breadcrumbs=array(
	'Drivers'=>array('index'),
	$model->user_id,
);

$this->menu=array(
	array('label'=>'List Driver', 'url'=>array('index')),
	array('label'=>'Create Driver', 'url'=>array('create')),
	array('label'=>'Update Driver', 'url'=>array('update', 'id'=>$model->user_id)),
	array('label'=>'Delete Driver', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->user_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Driver', 'url'=>array('admin')),
);
?>

<h1>View Driver #<?php echo $model->user_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'user_id',
		'routing_code',
	),
)); ?>
