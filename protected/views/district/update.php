<?php
$this->breadcrumbs=array(
	'Province'=>array('admin'),
	'Districts '.$model->province->name => array('province/view','id'=>$model->province_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List District', 'url'=>array('index')),
	array('label'=>'Create District', 'url'=>array('create')),
	array('label'=>'View District', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage District', 'url'=>array('admin')),
);
?>

<h1>Update District <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'zone_list'=>  CHtml::listData($zone, 'id', 'name'))); ?>