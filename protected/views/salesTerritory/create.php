<?php
/* @var $this SalesTeritoryController */
/* @var $model SalesTeritory */

$this->breadcrumbs=array(
	'Sales Teritories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SalesTeritory', 'url'=>array('index')),
);
?>

<h1>Create SalesTeritory</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>