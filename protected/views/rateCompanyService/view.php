<?php
$this->breadcrumbs=array(
	'Rate Company Services'=>array('index'),
	$model->id,
);

$this->menu=array(

	array('label'=>'Manage RateCompany', 'url'=>array('admin')),
);
?>

<h1>View RateCompanyService #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'company_id',
	),
)); ?>
