<div class="view">

	<div class="view-data">
		<div class="label"><?php echo CHtml::encode($data->getAttributeLabel('accountnr')); ?></div>
		<div class="data"><?php echo CHtml::encode($data->accountnr); ?></div>
		<div class="clearfix"></div>
	</div>
	
	<div class="view-data">
		<div class="label"><?php echo CHtml::encode('Account Name'); ?></div>
		<div class="data"><?php echo CHtml::encode($data->getContactData()->full_name); ?></div>
		<div class="clearfix"></div>
	</div>
	
	<div class="view-data">
		<div class="label"><?php echo CHtml::encode($data->getContactData()->getAttributeLabel('address')); ?></div>
		<div class="data"><?php echo CHtml::encode($data->getContactData()->address); ?></div>
		<div class="clearfix"></div>
	</div>

</div>