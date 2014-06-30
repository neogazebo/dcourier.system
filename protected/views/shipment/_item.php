<?php echo CHtml::activeHiddenField($item, "[$index]id"); ?>
<div class="item-container">
	<div class="i-title"><?php echo CHtml::activeTextField($item, "[$index]title"); ?></div>
	<div class="i-value"><?php echo CHtml::activeTextField($item, "[$index]amount", array('size' => '10')); ?></div>
	<div class="i-weight"><?php echo CHtml::activeTextField($item, "[$index]package_weight", array('size' => 5)); ?></div>
	<div class="i-length"><?php echo CHtml::activeTextField($item, "[$index]package_length", array('size' => 3)); ?></div>
	<div class="i-width"><?php echo CHtml::activeTextField($item, "[$index]package_width", array('size' => 3)); ?></div>
	<div class="i-height"><?php echo CHtml::activeTextField($item, "[$index]package_height", array('size' => 3)); ?></div>
	<div class="i-volmat"><?php echo CHtml::activeTextField($item, "[$index]package_weight_vol", array('disabled' => 'disabled', 'size' => 6)); ?></div>
	<div class="clearfix"></div>
</div>
