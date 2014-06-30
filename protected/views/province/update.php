<?php
$this->breadcrumbs=array(
	'Provinces'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Create Province', 'url'=>array('create')),
	array('label'=>'View Province', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Province', 'url'=>array('admin')),
);
?>

<h4 class="ui-box-header ui-corner-all">Update Province <?php echo $model->name; ?></h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>