<div class="form wide">
	<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'update-shipment-form',
		'enableAjaxValidation' => false,
		'action' => Yii::app()->createUrl('shipment/update',array('id'=>$shipment->id))
			));

	?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<fieldset>
		<div class="subcolumns">
			<div class="c50l">
<!--				<div class="row">
					<?php // echo $form->labelEx($shipment, 'pickup_date') ?>
					<?php // echo $form->textField($shipment, 'pickup_date') ?>
					<?php // echo $form->error($shipment, 'pickup_date') ?>
				</div>-->
				<div class="row">
					<?php echo CHtml::activeLabelEx($customer, 'accountnr') ?>
					<?php echo CHtml::activeTextField($customer, 'accountnr',array('disabled' => 'disabled')) ?>
				</div>
			</div>
			<div class="c50r">
				<div class="row">
					<?php echo $form->labelEx($shipment, 'awb') ?>
					<?php echo $form->textField($shipment, 'awb') ?>
					<?php echo $form->error($shipment, 'awb') ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($shipment, 'carrier_awb') ?>
					<?php echo $form->textField($shipment, 'carrier_awb') ?>
					<?php echo $form->error($shipment, 'carrier_awb') ?>
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<div class="subcolumns">
			<div class="c50l">
				<div class="row">
					<?php echo $form->labelEx($shipment, 'shipper_name') ?>
					<?php echo $form->textField($shipment, 'shipper_name') ?>
					<?php echo $form->error($shipment, 'shipper_name') ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($shipment, 'shipper_address') ?>
					<?php echo $form->textField($shipment, 'shipper_address') ?>
					<?php echo $form->error($shipment, 'shipper_address') ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($shipment, 'shipper_city') ?>
					<?php echo $form->textField($shipment, 'shipper_city') ?>
					<?php echo $form->error($shipment, 'shipper_city') ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($shipment, 'shipper_postal') ?>
					<?php echo $form->textField($shipment, 'shipper_postal') ?>
					<?php echo $form->error($shipment, 'shipper_postal') ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($shipment, 'shipper_phone') ?>
					<?php echo $form->textField($shipment, 'shipper_phone') ?>
					<?php echo $form->error($shipment, 'shipper_phone') ?>
				</div>
			</div>
			<div class="c50r">
				<div class="row">
					<?php echo $form->labelEx($shipment, 'receiver_name') ?>
					<?php echo $form->textField($shipment, 'receiver_name') ?>
					<?php echo $form->error($shipment, 'receiver_name') ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($shipment, 'receiver_address') ?>
					<?php echo $form->textField($shipment, 'receiver_address') ?>
					<?php echo $form->error($shipment, 'receiver_address') ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($shipment, 'receiver_city') ?>
					<?php echo $form->textField($shipment, 'receiver_city') ?>
					<?php echo $form->error($shipment, 'receiver_city') ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($shipment, 'receiver_postal') ?>
					<?php echo $form->textField($shipment, 'receiver_postal') ?>
					<?php echo $form->error($shipment, 'receiver_postal') ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($shipment, 'receiver_phone') ?>
					<?php echo $form->textField($shipment, 'receiver_phone') ?>
					<?php echo $form->error($shipment, 'receiver_phone') ?>
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<div class="subcolumns">
			<div class="c50l">
				<div class="row">
					<?php echo $form->labelEx($shipment, 'type') ?>
					<?php echo $form->textField($shipment, 'type') ?>
					<?php echo $form->error($shipment, 'type') ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($shipment, 'pieces') ?>
					<?php echo $form->textField($shipment, 'pieces') ?>
					<?php echo $form->error($shipment, 'pieces') ?>
				</div>
<!--				<div class="row">
					<?php // echo $form->labelEx($shipment, 'pickup_note') ?>
					<?php // echo $form->textField($shipment, 'pickup_note') ?>
					<?php // echo $form->error($shipment, 'pickup_note') ?>
				</div>-->
			</div>
			<div class="c50r">
				<div class="row">
					<?php echo $form->labelEx($shipment, 'package_weight') ?>
					<?php echo $form->textField($shipment, 'package_weight') ?>
					<?php echo $form->error($shipment, 'package_weight') ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($shipment, 'package_value') ?>
					<?php echo $form->textField($shipment, 'package_value') ?>
					<?php echo $form->error($shipment, 'package_value') ?>
				</div>
			</div>
		</div>
	</fieldset>
	<div class="row">
		<?php echo CHtml::submitButton('Save'); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>