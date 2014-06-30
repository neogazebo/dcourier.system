<?php
$this->breadcrumbs=array(
	'Companies'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Company', 'url'=>array('index')),
);
?>

<h4 class="ui-box-header ui-corner-all">
	Create Rate Company
</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>