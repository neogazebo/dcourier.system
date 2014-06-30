<?php
$this->breadcrumbs=array(
	'Origins'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Manage Origins', 'url'=>array('admin')),
);
?>

<h4 class="ui-box-header ui-corner-all">Update Origins <?php echo $model->id; ?></h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>