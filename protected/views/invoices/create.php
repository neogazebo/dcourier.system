<?php
$this->breadcrumbs = array(
	'Invoices' => array('index'),
	'Create',
);

$this->menu = array(
	array('label' => 'List Invoices', 'url' => array('viewInvoice', 'id' => $customer->id)),
);

?>

<h4 class="ui-box-header ui-corner-all">Create Invoices</h4>

<?php
echo $this->renderPartial('_form', array(
	'model' => $model,
	'formInvoice' => $formInvoice,
	'customer' => $customer,
	'cust_transaction' => $cust_transaction
	));
?>