<?php
/* @var $this JobController */
/* @var $model Job */

$this->breadcrumbs = array(
	'Create',
);

$this->renderPartial('_formAWB',array(
	'shipment'=>$shipment,
	'customer'=>$customer
));
?>


