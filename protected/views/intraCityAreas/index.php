<?php
$this->breadcrumbs=array(
	'Intra City Areases',
);

$this->menu=array(
	array('label'=>'Create IntraCityAreas', 'url'=>array('create')),
	array('label'=>'Manage IntraCityAreas', 'url'=>array('admin')),
);
?>

<h1>Intra City Areases</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
