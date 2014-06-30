<?php
$this->breadcrumbs=array(
	'Companies'=>array('rateCompany/index'),
	$model->rateCompany->name. ' Services'=>array('index', 'rate_company_id'=>$model->rate_company_id),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage '.$model->rateCompany->name.' Services', 'url'=>array('index','rate_company_id'=>$model->rate_company_id)),
);
?>
<h1>Create <?php echo $model->rateCompany->name ?> Service</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>