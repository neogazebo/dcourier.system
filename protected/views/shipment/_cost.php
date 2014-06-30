<div class="cost-container">
	<div class="c-name"><?php echo CHtml::activeTextField($cost, "[$index]name"); ?></div>
	<div class="c-desc"><?php echo CHtml::activeTextField($cost, "[$index]desc", array('size' => '10')); ?></div>
	<div class="c-cost"><?php echo CHtml::activeTextField($cost, "[$index]cost", array('size' => 5,'class'=>'calculate add_cost')); ?></div>
	<?php echo CHtml::activeHiddenField($cost, "[$index]id"); ?>
	<div class="clearfix"></div>
</div>