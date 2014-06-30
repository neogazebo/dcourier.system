<?php
$this->breadcrumbs = array(
		'Create'
);

$this->menu=array(
//	array('label'=>'List Area', 'url'=>array('zone/view','id'=>$model->zone_id)),
);
?>

<h4 class="ui-box-header ui-corner-all">
	Create Shipping Profile
</h4>

<?php echo $this->renderPartial('_form_shipping_profile', array(
	'customer_shipping_profile' => $customer_shipping_profile,
	'product_id' => 0,
	)); ?>