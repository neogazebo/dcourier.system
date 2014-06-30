<?php
$this->breadcrumbs=array(
	'Area Codes',
);

$this->menu=array(
	array('label'=>'Create AreaCodes', 'url'=>array('create')),
	array('label'=>'Manage AreaCodes', 'url'=>array('admin')),
);
?>

<h1>Area Codes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
