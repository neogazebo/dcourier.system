<?php
$this->breadcrumbs = array(
	'Shipments' => array('admin'),
	'Create',
);

$this->menu = array(
	array('label' => 'Manage Shipment', 'url' => array('admin')),
);

$data_render = array(
	'shipment' => $shipment,
	'inquiry' => $inquiry,
	'add_costs' => $add_costs,
	'items' => $items,
	'customer' => $customer,
	'contact' => $contact,
	'services' => $services,
	'group' => $group,
	'intraCityArea' => $intraCityArea,
);

$script = <<<EOD
$('#tabs').tabs();
EOD;
$css = Yii::app()->assetManager->publish(Yii::getPathOfAlias('system.web.js.source.jui.css.base.jquery-ui') . '.css');
$cs = Yii::app()->clientScript;
$cs->registerCssFile($css);
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerScript('formPickup', $script);

?>

<h4 class="ui-box-header ui-corner-all">
	Pick Up
</h4>

<?php echo $this->renderPartial('_formPickup', $data_render); ?>