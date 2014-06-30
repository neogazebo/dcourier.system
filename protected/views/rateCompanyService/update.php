<?php
$this->breadcrumbs=array(
	'Companies'=>array('rateCompany/index'),
	$model->rateCompany->name. ' Services'=>array('index', 'rate_company_id'=>$model->rate_company_id),
	'Update',
);

$this->menu=array(
	array('label'=>'Manage '.$model->rateCompany->name.' Services', 'url'=>array('index','rate_company_id'=>$model->rate_company_id)),
);
?>

<h1>Update Company Service <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>