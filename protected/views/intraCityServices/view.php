<?php
$this->breadcrumbs=array(
	'Intra City Services'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List IntraCityServices', 'url'=>array('index')),
	array('label'=>'Create IntraCityServices', 'url'=>array('create')),
	array('label'=>'Update IntraCityServices', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete IntraCityServices', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage IntraCityServices', 'url'=>array('admin')),
);
?>

<h1>View IntraCityServices #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'area_id',
		'type_id',
		'price',
		'weight',
	),
)); ?>
