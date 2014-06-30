<?php
$this->breadcrumbs=array(
	'Good Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage GoodType', 'url'=>array('admin')),
);
?>

<h4 class="ui-box-header ui-corner-all">
	Create Good Type
</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>