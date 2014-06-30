<?php
$this->breadcrumbs=array(
	'Good Types'=>array('index'),
	$model->code=>array('view','id'=>$model->code),
	'Update',
);

$this->menu=array(
	array('label'=>'Manage GoodType', 'url'=>array('admin')),
);
?>

<h4 class="ui-box-header ui-corner-all">Update Good Type <?php echo $model->code; ?></h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>