<?php
$this->breadcrumbs=array(
	'Intra City Services'=>array('admin'),
	'Update',
);

$this->menu=array(
	array('label'=>'List Intra City Rates', 'url'=>array('admin')),
);
?>

<h4 class="ui-box-header ui-corner-all">Update IntraCityServices <?php echo $model->id; ?></h4>

<?php echo $this->renderPartial('_form', array('model'=>$model,'types'=> CHtml::listData($types, 'id', 'name'),'areas'=>CHtml::listData($areas, 'id', 'name') )); ?>