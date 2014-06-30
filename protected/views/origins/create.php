<?php
$this->breadcrumbs=array(
	'Origins'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Origins', 'url'=>array('admin')),
);
?>

<h4 class="ui-box-header ui-corner-all">Create Origins</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>