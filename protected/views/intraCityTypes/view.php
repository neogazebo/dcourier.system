<?php
$this->breadcrumbs=array(
	'Intra City Types'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List IntraCityTypes', 'url'=>array('index')),
	array('label'=>'Create IntraCityTypes', 'url'=>array('create')),
	array('label'=>'Update IntraCityTypes', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete IntraCityTypes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage IntraCityTypes', 'url'=>array('admin')),
);
?>

<h1>View IntraCityTypes #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
