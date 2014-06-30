<?php
$this->breadcrumbs=array(
	'Origins',
);

$this->menu=array(
	array('label'=>'Create Origins', 'url'=>array('create')),
	array('label'=>'Manage Origins', 'url'=>array('admin')),
);
?>

<h1>Origins</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
