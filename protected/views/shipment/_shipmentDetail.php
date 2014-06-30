<div id="tabs">
	<ul>
		<li><?php echo CHtml::link("Shipper", '#tabs-1') ?></li>
		<li><?php echo CHtml::link("Consignee", '#tabs-2') ?></li>
		<li><?php echo CHtml::link("Shipment", '#tabs-3') ?></li>
	</ul>
	
	<div class="form">
		<div id="tabs-1">
			<div class="row">
				<?php echo $form->labelEx($shipment, 'shipper_name') ?>
				<?php echo $form->textField($shipment, 'shipper_name', array('value' => $customer->name)) ?>
				<?php echo $form->error($shipment, 'shipper_name') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'shipper_address') ?>
				<?php echo $form->textArea($shipment, 'shipper_address', array('value' => $contact->address)) ?>
				<?php echo $form->error($shipment, 'shipper_address') ?>
			</div>

			<div class="subcolumns">
				<div class="c48l">

					<div class="row">
						<?php echo $form->labelEx($shipment, 'shipper_country') ?>
						<?php echo $form->textField($shipment, 'shipper_country', array('value' => $contact->country)) ?>
						<?php echo $form->error($shipment, 'shipper_country') ?>
					</div>


					<div class="row">
						<?php echo $form->labelEx($shipment, 'shipper_city') ?>
						<?php echo $form->textField($shipment, 'shipper_city', array('value' => $contact->city)) ?>
						<?php echo $form->error($shipment, 'shipper_city') ?>
					</div>

					<div class="row">
						<?php echo $form->labelEx($shipment, 'shipper_postal') ?>
						<?php echo $form->textField($shipment, 'shipper_postal', array('value' => $contact->postal)) ?>
						<?php echo $form->error($shipment, 'shipper_postal') ?>
					</div>

				</div>
				<div class="c48r">
					<div class="row">
						<?php echo $form->labelEx($shipment, 'shipper_phone') ?>
						<?php echo $form->textField($shipment, 'shipper_phone', array('value' => $contact->phone1)) ?>
						<?php echo $form->error($shipment, 'shipper_phone') ?>
					</div>
					
				</div>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'shipper_email') ?>
				<?php echo $form->textField($shipment, 'shipper_email', array('value' => $contact->email)) ?>
				<?php echo $form->error($shipment, 'shipper_email') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'shipper_numberID') ?>
				<?php echo $form->textField($shipment, 'shipper_numberID', array('value' => $customer->numberID)) ?>
				<?php echo $form->error($shipment, 'shipper_numberID') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'shipper_comment') ?>
				<?php echo $form->textArea($shipment, 'shipper_comment', array('value' => $customer->comments)) ?>
				<?php echo $form->error($shipment, 'shipper_comment') ?>
			</div>

		</div>
		<div id="tabs-2">
			<div class="row">
				<?php echo CHtml::label('Account Number', ''); ?>
				<?php echo CHtml::textField('customer_account') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'receiver_name') ?>
				<?php echo $form->textField($shipment, 'receiver_name') ?>
				<?php echo $form->error($shipment, 'receiver_name') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'receiver_address') ?>
				<?php echo $form->textArea($shipment, 'receiver_address') ?>
				<?php echo $form->error($shipment, 'receiver_address') ?>
			</div>

			<div class="subcolumns">
				<div class="c48l">
					<div class="row">
						<?php echo $form->labelEx($shipment, 'receiver_country') ?>
						<?php echo $form->textField($shipment, 'receiver_country', array('value' => $inquiry->receiver_country)) ?>
						<?php echo $form->error($shipment, 'receiver_country') ?>
					</div>

					<div class="row">
						<?php echo $form->labelEx($shipment, 'receiver_city') ?>
						<?php echo $form->textField($shipment, 'receiver_city', array('value' => $inquiry->receiver_city)) ?>
						<?php echo $form->error($shipment, 'receiver_city') ?>
					</div>

					<div class="row">
						<?php echo $form->labelEx($shipment, 'receiver_postal') ?>
						<?php echo $form->textField($shipment, 'receiver_postal', array('value' => $inquiry->receiver_postal)) ?>
						<?php echo $form->error($shipment, 'receiver_postal') ?>
					</div>
				</div>
				<div class="c48r">
					<div class="row">
						<?php echo $form->labelEx($shipment, 'receiver_phone') ?>
						<?php echo $form->textField($shipment, 'receiver_phone') ?>
						<?php echo $form->error($shipment, 'receiver_phone') ?>
					</div>
				</div>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'receiver_email') ?>
				<?php echo $form->textField($shipment, 'receiver_email') ?>
				<?php echo $form->error($shipment, 'receiver_email') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'receiver_numberID') ?>
				<?php echo $form->textField($shipment, 'receiver_numberID') ?>
				<?php echo $form->error($shipment, 'receiver_numberID') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'receiver_comment') ?>
				<?php echo $form->textArea($shipment, 'receiver_comment') ?>
				<?php echo $form->error($shipment, 'receiver_comment') ?>
			</div>
		</div>
		<div id="tabs-3">
			<?php $randawb = rand(1000000000, 9999999999); ?>
			<div class="row">
				<?php echo $form->labelEx($shipment, 'awb'); ?>
				<?php echo $form->textField($shipment, 'awb', array('disabled' => 'disabled', 'value' => $shipment->isNewRecord ? $randawb : $shipment->awb)); ?>
				<?php echo CHtml::checkBox('sendmail') ?> Send email
				<?php echo $form->hiddenField($shipment, 'awb', array('value' => $shipment->isNewRecord ? $randawb : $shipment->awb)); ?>
				<?php echo $form->error($shipment, 'awb'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'commodity') ?>
				<?php echo $form->textField($shipment, 'commodity', array('value' => $inquiry->commodity)) ?>
<?php echo $form->error($shipment, 'commodity') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'packaging') ?>
				<?php echo $form->textField($shipment, 'packaging') ?>
<?php echo $form->error($shipment, 'packaging') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'remarks') ?>
				<?php echo $form->textField($shipment, 'remarks') ?>
<?php echo $form->error($shipment, 'remarks') ?>
			</div>

		</div>
	</div>
</div>
<div class="row buttons">
	<?php echo CHtml::button('Pickup' ,array('id' => 'pckp')) ?>
</div>
<?php
$script = <<<EOD
$('#tabs').tabs();
EOD;
$css = Yii::app()->assetManager->publish(Yii::getPathOfAlias('system.web.js.source.jui.css.base.jquery-ui') . '.css');
$cs = Yii::app()->clientScript;
$cs->registerCssFile($css);
$cs->registerScript('formPickup', $script);
$pickup_script = <<<EOD
	$("#pckp").click(function(){
		window.location = "#pickup-detail";
	})
EOD;
$cs->registerScript('pickup',$pickup_script);
?>
