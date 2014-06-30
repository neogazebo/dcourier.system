<?php
$this->breadcrumbs=array(
	'Pickups',
);

$this->menu=array(
	array('label'=>'Create Pickup', 'url'=>array('create')),
	array('label'=>'Manage Pickup', 'url'=>array('admin')),
);
?>

<h1>Pickups</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
