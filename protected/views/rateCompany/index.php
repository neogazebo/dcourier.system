<?php
$this->breadcrumbs=array(
	'Rate Companys',
);

$this->menu=array(
	array('label'=>'Create RateCompany', 'url'=>array('create')),
	array('label'=>'Manage RateCompany', 'url'=>array('admin')),
);
?>

<h1>Rate Companys</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
