<?php
$this->breadcrumbs=array(
	'Good Types'=>array('index'),
	$model->code,
);

$this->menu=array(
	array('label'=>'Create GoodType', 'url'=>array('create')),
	array('label'=>'Update GoodType', 'url'=>array('update', 'id'=>$model->code)),
	array('label'=>'Delete GoodType', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->code),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GoodType', 'url'=>array('admin')),
);
?>

<h1>View GoodType #<?php echo $model->code; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'code',
		'desc',
	),
)); ?>
