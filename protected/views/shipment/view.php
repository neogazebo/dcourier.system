<h4 class="ui-box-header ui-corner-all">Pickup Confirmation for <?php echo $customer->name ?></h4>
<br />
<?php
$this->widget('zii.widgets.CDetailView', array(
	'data' => $shipment,
	'attributes' => array(
		'awb',
		'shipper_name',
		'shipper_country',
		'shipper_province',
		'shipper_city',
		'shipper_address',
		'shipper_phone',
		'shipper_fax',
		'receiver_name',
		'receiver_country',
		'receiver_province',
		'receiver_city',
		'receiver_address',
		'receiver_phone',
		'receiver_fax',
		'charges',
		'type',
		'package_weight',
		'service_type',
		array(
			'label' => 'Payment Method',
			'type' => 'raw',
			'value' => $shipment->generateAttributeLabel($shipment->pay_by),
		)
	),
));

?>

<br />
<?php
$form = $this->beginWidget('CActiveForm', array(
	'id' => 'pickup-form',
	'enableAjaxValidation' => false,
		));

?>
<div class="row buttons">
	<?php echo CHtml::submitButton('OK'); ?>
</div>

<?php $this->endWidget(); ?>