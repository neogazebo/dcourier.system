<?php
$this->breadcrumbs=array(
	'Intra City Types',
);

$this->menu=array(
	array('label'=>'Create IntraCityTypes', 'url'=>array('create')),
	array('label'=>'Manage IntraCityTypes', 'url'=>array('admin')),
);
?>

<h1>Intra City Types</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
