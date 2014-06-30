<?php
$this->breadcrumbs = array(
	'Companies' => array('rateCompany/index'),
	$model->rateCompanyService->rateCompany->name . ' Services' => array('rateCompanyService/index', 'rate_company_id' => $model->rateCompanyService->rateCompany->id),
	'Service Detail'=>array('serviceDetail/index','company_service_id'=>$model->rate_company_service_id),
	'Create',
);

$this->menu = array(
	array('label' => 'List ServiceDetail', 'url' => array('serviceDetail/index','company_service_id'=>$model->rate_company_service_id)),
);

?>

<h1>Create ServiceDetail</h1>

<?php
echo $this->renderPartial('_form', array(
	'model' => $model,
	'availableProductServices' => $availableProductServices
));

?>