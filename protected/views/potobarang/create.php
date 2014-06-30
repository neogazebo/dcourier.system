<?php
/* @var $this PotobarangController */
/* @var $model PotoBarang */

$this->breadcrumbs=array(
	'Poto Barangs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PotoBarang', 'url'=>array('index')),
	array('label'=>'Manage PotoBarang', 'url'=>array('admin')),
);
?>

<h1>Create PotoBarang</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>