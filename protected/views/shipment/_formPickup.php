<fieldset>
	<legend>PickUp Detail</legend>
		<div class="row">
		<?php echo $form->labelEx($shipment, 'pickup_date') ?>
		<?php echo $form->textField($shipment, 'pickup_date', array('value' => $inquiry->pickup_date)) ?>
		<?php echo $form->error($shipment, 'pickup_date') ?>
	</div>

	<div class="subcolumns">
		<div class="c48l">
			<div class="row">
				<?php echo $form->labelEx($shipment, 'payer') ?>
				<?php echo $form->DropdownList($shipment, 'payer', $shipment->listpayer, array('prompt' => '-- Payer --')) ?>
				<?php echo $form->error($shipment, 'payer') ?>
			</div>
		</div>
		<div class="c48r">
			<div class="row">
				<?php echo $form->labelEx($shipment, 'pay_by') ?>
				<?php echo $form->DropdownList($shipment, 'pay_by', $shipment->listpayby, array('prompt' => '-- Pay By --')) ?>
				<?php echo $form->error($shipment, 'pay_by') ?>
			</div>
		</div>
	</div>

	<div class="row">
		<?php echo $form->labelEx($shipment, 'pickup_name') ?>
		<?php echo $form->textField($shipment, 'pickup_name', array('value' => $customer->name)) ?>
		<?php echo $form->error($shipment, 'pickup_name') ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($shipment, 'pickup_address') ?>
		<?php echo $form->textArea($shipment, 'pickup_address') ?>
		<?php echo $form->error($shipment, 'pickup_address') ?>
	</div>

	<div class="subcolumns">
		<div class="c48l">
			<div class="row">
				<?php echo $form->labelEx($shipment, 'pickup_city') ?>
				<?php echo $form->textField($shipment, 'pickup_city', array('value' => $contact->city)) ?>
				<?php echo $form->error($shipment, 'pickup_city') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'pickup_phone') ?>
				<?php echo $form->textField($shipment, 'pickup_phone', array('value' => $contact->phone1)) ?>
				<?php echo $form->error($shipment, 'pickup_phone') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'pickup_address_type') ?>
				<?php echo $form->DropdownList($shipment, 'pickup_address_type', $shipment->listpickupaddresstype, array('prompt' => '-- Address Type --')) ?>
				<?php echo $form->error($shipment, 'pickup_address_type') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'shipment_location') ?>
				<?php echo $form->textField($shipment, 'shipment_location') ?>
				<?php echo $form->error($shipment, 'shipment_location') ?>
			</div>

		</div>
		<div class="c48r">
			<div class="row">
				<?php echo $form->labelEx($shipment, 'pickup_postal') ?>
				<?php echo $form->textField($shipment, 'pickup_postal') ?>
				<?php echo $form->error($shipment, 'pickup_postal') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'pickup_fax') ?>
				<?php echo $form->textField($shipment, 'pickup_fax') ?>
				<?php echo $form->error($shipment, 'pickup_fax') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'office_close_time') ?>
				<?php echo $form->textField($shipment, 'office_close_time',array('size' => 5)) ?>
				<?php echo $form->error($shipment, 'office_close_time') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'shipment_ready_time') ?>
				<?php echo $form->textField($shipment, 'shipment_ready_time',array('size' => 5)) ?>
				<?php echo $form->error($shipment, 'shipment_ready_time') ?>
			</div>
		</div>
	</div>
	<div class="row">
		<?php echo $form->labelEx($shipment, 'pickup_note') ?>
		<?php echo $form->textArea($shipment, 'pickup_fax') ?>
		<?php echo $form->error($shipment, 'pickup_fax') ?>
	</div>
</fieldset>
<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery-timepicker/jquery.timepicker.min.js');
$cs->registerCssFile(Yii::app()->baseUrl.'/js/jquery-timepicker/jquery.timepicker.css');
$office_close_time = <<<EOF
	$("#Shipment_office_close_time,#Shipment_shipment_ready_time").timepicker({ 'timeFormat': 'H:i' });
EOF;
$cs->registerScript('office_close_time',$office_close_time);
?>