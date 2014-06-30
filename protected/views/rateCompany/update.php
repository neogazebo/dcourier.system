<?php
$this->breadcrumbs=array(
	'Companies'=>array('index'),
	'Update '.$model->name,
);

$this->menu=array(
	array('label'=>'Manage Companies', 'url'=>array('index')),
);
?>

<h1>Update RateCompany <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>