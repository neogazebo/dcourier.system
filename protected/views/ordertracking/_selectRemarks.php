<?php if(count($remarks) != 0): ?>
<div class="row">
	<?php echo CHtml::label('Remark', 'ShipmentEvent_remark') ?>
	<?php echo CHtml::dropDownList('ShipmentEvent[remark]', '', CHtml::listData($remarks, 'id', 'name'), array('id' => 'ShipmentEvent_remark')) ?>
</div>
<?php endif; ?>
<?php if ($status_id == 10): ?>
	<h4>Recipient Detail</h4>
	<div class="row">
		<?php echo CHtml::label('Recipient Name', 'recipient_name') ?>
		<?php echo CHtml::textField('ShipmentEvent[recipient_name]') ?>
	</div>
	<div class="row">
		<?php echo CHtml::label('Recipient Title', 'recipient_title') ?>
		<?php echo CHtml::textField('ShipmentEvent[recipient_title]') ?>
	</div>
	<div class="row">
		<?php echo CHtml::label('Recipient Date', 'recipient_date') ?>
		<?php
		$this->widget('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker', array(
			'attribute' => 'recipient_date',
			'value' => date('m/d/Y',time()),
			'name' => 'ShipmentEvent[recipient_date]',
			'language' => '',
			'options' => array(
				'yearRange' => '-0:+7',
				'changeYear' => 'true',
				'changeMonth' => 'true',
			),
		));
		?>
	</div>
<?php endif; ?>