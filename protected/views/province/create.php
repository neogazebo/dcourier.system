<?php
$this->breadcrumbs=array(
	'Provinces'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Province', 'url'=>array('index')),
	array('label'=>'Manage Province', 'url'=>array('admin')),
);
?>

<h4 class="ui-box-header ui-corner-all">
	Create Province
</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>