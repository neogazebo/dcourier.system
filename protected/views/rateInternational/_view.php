<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('weight')); ?>:</b>
	<?php echo CHtml::encode($data->weight); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('zone_a')); ?>:</b>
	<?php echo CHtml::encode($data->zone_a); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('zone_b')); ?>:</b>
	<?php echo CHtml::encode($data->zone_b); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('zone_c')); ?>:</b>
	<?php echo CHtml::encode($data->zone_c); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('zone_d')); ?>:</b>
	<?php echo CHtml::encode($data->zone_d); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('zone_e')); ?>:</b>
	<?php echo CHtml::encode($data->zone_e); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('zone_f')); ?>:</b>
	<?php echo CHtml::encode($data->zone_f); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('zone_g')); ?>:</b>
	<?php echo CHtml::encode($data->zone_g); ?>
	<br />

	*/ ?>

</div>