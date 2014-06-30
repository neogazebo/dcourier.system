<?php
$this->breadcrumbs=array(
	'Rate Company Services',
);

$this->menu=array(
	array('label'=>'Manage RateCompanyService', 'url'=>array('admin')),
);
?>

<h1>Rate Company Services</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
