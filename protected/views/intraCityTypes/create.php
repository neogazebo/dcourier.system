<?php
$this->breadcrumbs=array(
	'Intra City Services'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Intra City Services', 'url'=>array('admin')),
);
?>

<h4 class="ui-box-header ui-corner-all">Create IntraCityTypes</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>