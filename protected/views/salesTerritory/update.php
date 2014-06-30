<?php
/* @var $this SalesTeritoryController */
/* @var $model SalesTeritory */

$this->breadcrumbs=array(
	'Sales Teritories'=>array('index'),
	$model->users_id=>array('view','id'=>$model->users_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SalesTeritory', 'url'=>array('index')),
	array('label'=>'Create SalesTeritory', 'url'=>array('create')),
);
?>

<h1>Update SalesTeritory <?php echo $model->users_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>