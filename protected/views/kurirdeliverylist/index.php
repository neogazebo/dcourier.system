<?php
/* @var $this KurirdeliverylistController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Kurir Delivery Lists',
);

$this->menu=array(
	array('label'=>'Create KurirDeliveryList', 'url'=>array('create')),
	array('label'=>'Manage KurirDeliveryList', 'url'=>array('admin')),
);
?>

<h1>Kurir Delivery Lists</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
