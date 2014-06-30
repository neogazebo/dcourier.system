<?php
/* @var $this DriverLocationController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Driver Locations',
);

$this->menu=array(
	array('label'=>'Create DriverLocation', 'url'=>array('create')),
	array('label'=>'Manage DriverLocation', 'url'=>array('admin')),
);
?>

<h1>Driver Locations</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
