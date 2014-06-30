<?php
$this->breadcrumbs=array(
	'Zone Country Services'=>array('index'),
	'Update',
);

$this->menu=array(
	array('label'=>'List Zone Country', 'url'=>array('admin')),
);
?>

<h4 class="ui-box-header ui-corner-all">Update Zone Country <?php echo $model->country; ?></h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>