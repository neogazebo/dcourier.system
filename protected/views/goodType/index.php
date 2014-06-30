<?php
$this->breadcrumbs=array(
	'Good Types',
);

$this->menu=array(
	array('label'=>'Create GoodType', 'url'=>array('create')),
	array('label'=>'Manage GoodType', 'url'=>array('admin')),
);
?>

<h1>Good Types</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
