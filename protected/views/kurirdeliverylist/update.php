<?php
/* @var $this KurirdeliverylistController */
/* @var $model KurirDeliveryList */

$this->breadcrumbs=array(
	'Kurir Delivery Lists'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List KurirDeliveryList', 'url'=>array('index')),
	array('label'=>'Create KurirDeliveryList', 'url'=>array('create')),
	array('label'=>'View KurirDeliveryList', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage KurirDeliveryList', 'url'=>array('admin')),
);
?>

<h1>Update KurirDeliveryList <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>