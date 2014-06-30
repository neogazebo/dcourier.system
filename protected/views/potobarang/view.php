<?php
/* @var $this PotobarangController */
/* @var $model PotoBarang */

$this->breadcrumbs=array(
	'Poto Barangs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List PotoBarang', 'url'=>array('index')),
	array('label'=>'Create PotoBarang', 'url'=>array('create')),
	array('label'=>'Update PotoBarang', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete PotoBarang', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PotoBarang', 'url'=>array('admin')),
);
?>

<h1>View PotoBarang #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'users_id',
		'time',
		'image',
	),
)); ?>
