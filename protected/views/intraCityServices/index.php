<?php
$this->breadcrumbs=array(
	'Intra City Services',
);

$this->menu=array(
	array('label'=>'Create IntraCityServices', 'url'=>array('create')),
	array('label'=>'Manage IntraCityServices', 'url'=>array('admin')),
);
?>

<h1>Intra City Services</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
