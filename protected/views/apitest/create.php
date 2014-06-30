<?php
$this->breadcrumbs=array(
	'Shipments'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Shipment', 'url'=>array('admin')),
);
?>

<h4 class="ui-box-header ui-corner-all">
	Testing Request PickUp Service API Form
</h4>
<br />

<?php echo $this->renderPartial('_form', array('model'=>$model, 'model_city'=>$model_city,'model_domestic'=>$model_domestic,'model_international'=>$model_international,'goods_type'=>  CHtml::listData($good_types, 'code', 'desc'))); ?>

