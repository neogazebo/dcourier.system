<?php
/* @var $this KurirdeliverylistController */
/* @var $model KurirDeliveryList */

$this->breadcrumbs=array(
	'Kurir Delivery Lists'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List KurirDeliveryList', 'url'=>array('index')),
	array('label'=>'Manage KurirDeliveryList', 'url'=>array('admin')),
);
?>

<h1>Create KurirDeliveryList</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>