<?php
/* @var $this DriverController */
/* @var $model Driver */

$this->breadcrumbs=array(
	'Drivers'=>array('index'),
	$model->user_id=>array('view','id'=>$model->user_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Driver', 'url'=>array('index')),
	array('label'=>'Create Driver', 'url'=>array('create')),
	array('label'=>'View Driver', 'url'=>array('view', 'id'=>$model->user_id)),
	array('label'=>'Manage Driver', 'url'=>array('admin')),
);
?>

<h1>Update Driver <?php echo $model->user_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>