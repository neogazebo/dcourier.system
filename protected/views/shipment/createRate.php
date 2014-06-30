<?php
$this->breadcrumbs = array(
	'Shipments' => array('admin'),
	'Create',
);

$this->menu = array(
	array('label' => 'Manage Shipment', 'url' => array('admin')),
);

$data_render = array(
	'inquiry' => $inquiry,
	'items' => $items,
	'rateInquiry' => $rateInquiry,
	'costs' => $costs,
);

?>

<h4 class="ui-box-header ui-corner-all">
	Rate Inquiry
</h4>

<?php echo $this->renderPartial('_formRate', $data_render);?>