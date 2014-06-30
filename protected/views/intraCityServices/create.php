<?php
$this->breadcrumbs=array(
	'Intra City Services'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Intra City Rates', 'url'=>array('admin')),
);
?>

<h4 class="ui-box-header ui-corner-all">Create IntraCityServices</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model,'types'=> CHtml::listData($types, 'id', 'name'),'areas'=>CHtml::listData($areas, 'id', 'name') )); ?>