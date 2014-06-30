<?php
$this->breadcrumbs=array(
	'Customers'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Customer', 'url'=>array('admin')),
);
?>

<h4 class="ui-box-header ui-corner-all">
	Create Customer
</h4>

<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'contact'=>$contact,
	)); ?>