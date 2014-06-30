<?php
header('Content-type: text/javascript');
$shipment = Shipment::model();
$customer = Customer::model();
?>
<script type="text/javascript">
Ext.define('Job', {
	extend: 'Ext.data.Model',
	fields: <?php echo json_encode(array_keys($shipment->getMetaData()->columns)); ?>,
	validations: [
		{type: 'presence',  field: <?php echo CHtml::activeId($shipment, 'shipper_name') ?>}
	]
});
</script>