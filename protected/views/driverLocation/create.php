<?php
/* @var $this DriverLocationController */
/* @var $model DriverLocation */

$this->breadcrumbs=array(
	'Driver Locations'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DriverLocation', 'url'=>array('index')),
	array('label'=>'Manage DriverLocation', 'url'=>array('admin')),
);
?>

<h1>Create DriverLocation</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>