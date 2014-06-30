<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tgl_terbit')); ?>:</b>
	<?php echo CHtml::encode($data->tgl_terbit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tgl_pembayaran')); ?>:</b>
	<?php echo CHtml::encode($data->tgl_pembayaran); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tgl_jatuh_tempo')); ?>:</b>
	<?php echo CHtml::encode($data->tgl_jatuh_tempo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_id')); ?>:</b>
	<?php echo CHtml::encode($data->customer_id); ?>
	<br />


</div>