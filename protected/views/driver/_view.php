<?php
/* @var $this DriverController */
/* @var $data Driver */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->user_id), array('view', 'id'=>$data->user_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('routing_code')); ?>:</b>
	<?php echo CHtml::encode($data->routing_code); ?>
	<br />


</div>