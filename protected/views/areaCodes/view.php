<?php
$this->breadcrumbs=array(
	'Area Codes'=>array('index'),
	$model->code,
);

$this->menu=array(
	array('label'=>'List AreaCodes', 'url'=>array('index')),
	array('label'=>'Create AreaCodes', 'url'=>array('create')),
	array('label'=>'Update AreaCodes', 'url'=>array('update', 'id'=>$model->code)),
	array('label'=>'Delete AreaCodes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->code),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AreaCodes', 'url'=>array('admin')),
);
?>

<h1>View AreaCodes #<?php echo $model->code; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'code',
		'city',
		'main_city_code',
		'location',
	),
)); ?>
