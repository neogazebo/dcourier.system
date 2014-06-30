<div class="row service_list">
	<?php echo CHtml::activeLabelEx($inquiry, 'service_id') ?>
	<?php echo CHtml::activeDropDownList($inquiry, 'service_id', CHtml::listData($services, 'id', 'name', $group), array('prompt' => '-- Pilih Service --')) ?>
	<?php echo CHtml::error($inquiry, 'service_id') ?>
	<?php if($service_type == 'city'):  ?>
	<?php echo CHtml::activeDropDownList($inquiry, 'intra_city_area', CHtml::listData($intraCityArea, 'id', 'name'), array('prompt' => '-- Pilih Area --')) ?>
	<?php endif; ?>
</div>