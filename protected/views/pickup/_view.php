<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pickup_date')); ?>:</b>
	<?php echo CHtml::encode($data->pickup_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shipment_id')); ?>:</b>
	<?php echo CHtml::encode($data->shipment_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('driver_id')); ?>:</b>
	<?php echo CHtml::encode($data->driver_id); ?>
	<br />


</div>