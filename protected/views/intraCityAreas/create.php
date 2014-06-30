<?php
$this->breadcrumbs=array(
	'Intra City Areas'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Intra City Area', 'url'=>array('admin')),
);
?>

<h4 class="ui-box-header ui-corner-all">Create Intra City Area</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>