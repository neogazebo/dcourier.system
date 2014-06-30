<?php
/* @var $this SalesTeritoryController */
/* @var $data SalesTeritory */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('users_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->users_id), array('view', 'id'=>$data->users_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('territory')); ?>:</b>
	<?php echo CHtml::encode($data->territory); ?>
	<br />


</div>