<?php
/* @var $this DriverLocationController */
/* @var $model DriverLocation */

$this->breadcrumbs=array(
	'Driver Locations'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List DriverLocation', 'url'=>array('index')),
	array('label'=>'Create DriverLocation', 'url'=>array('create')),
	array('label'=>'View DriverLocation', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage DriverLocation', 'url'=>array('admin')),
);
?>

<h1>Update DriverLocation <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>