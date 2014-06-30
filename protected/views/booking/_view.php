<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('booking_code')); ?>:</b>
	<?php echo CHtml::encode($data->booking_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_id')); ?>:</b>
	<?php echo CHtml::encode($data->customer_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('request_by')); ?>:</b>
	<?php echo CHtml::encode($data->request_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
	<?php echo CHtml::encode($data->address); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('city')); ?>:</b>
	<?php echo CHtml::encode($data->city); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('postal')); ?>:</b>
	<?php echo CHtml::encode($data->postal); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('country')); ?>:</b>
	<?php echo CHtml::encode($data->country); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pickup_date')); ?>:</b>
	<?php echo CHtml::encode($data->pickup_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shipment_ready_time')); ?>:</b>
	<?php echo CHtml::encode($data->shipment_ready_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('office_close_time')); ?>:</b>
	<?php echo CHtml::encode($data->office_close_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address_type')); ?>:</b>
	<?php echo CHtml::encode($data->address_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pickup_note')); ?>:</b>
	<?php echo CHtml::encode($data->pickup_note); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shipment_location')); ?>:</b>
	<?php echo CHtml::encode($data->shipment_location); ?>
	<br />

	*/ ?>

</div>