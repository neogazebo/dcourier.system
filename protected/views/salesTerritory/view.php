<?php
/* @var $this SalesTeritoryController */
/* @var $model SalesTeritory */

$this->breadcrumbs=array(
	'Sales Teritories'=>array('index'),
	$model->users_id,
);

$this->menu=array(
	array('label'=>'List SalesTeritory', 'url'=>array('index')),
	array('label'=>'Create SalesTeritory', 'url'=>array('create')),
	array('label'=>'Update SalesTeritory', 'url'=>array('update', 'id'=>$model->users_id)),
	array('label'=>'Delete SalesTeritory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->users_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SalesTeritory', 'url'=>array('admin')),
);
?>

<h1>View SalesTeritory #<?php echo $model->users_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'users_id',
		'territory',
	),
)); ?>
