<?php
$this->breadcrumbs = array(
	'Customers' => array('admin'),
	'Update',
);


$menu = array(
	array('label' => 'Manage Customer', 'url' => array('admin')),
);
$this->menu = $menu;

?>

<h4 class="ui-box-header ui-corner-all">
	Update Customer <?php echo $model->name ?>
</h4>

<?php echo $this->renderPartial('_form', array(
	'model' => $model,
	'contact' => $contact,
	'customer_contacs' => $customer_contacs,
	'rekening' => $rekening,
	'customer_tokens' => $customer_tokens,
	'customer_shipping_profile' => $customer_shipping_profile
)); ?>