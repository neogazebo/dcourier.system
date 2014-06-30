<?php
$this->breadcrumbs=array(
	'Province'=>array('admin'),
	'Districts '.$model->province->name => array('province/view','id'=>$model->province_id),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage District', 'url'=>array('province/view','id'=>$model->province_id)),
);
?>

<h4 class="ui-box-header ui-corner-all">
	Create District For Province <?php echo $model->province->name?>
</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>