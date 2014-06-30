<?php
/* @var $this PotobarangController */
/* @var $model PotoBarang */

$this->breadcrumbs=array(
	'Poto Barangs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PotoBarang', 'url'=>array('index')),
	array('label'=>'Create PotoBarang', 'url'=>array('create')),
	array('label'=>'View PotoBarang', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage PotoBarang', 'url'=>array('admin')),
);
?>

<h1>Update PotoBarang <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>