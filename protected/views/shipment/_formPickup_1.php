<div id="tabs">
	<ul>
		<li><?php echo CHtml::link("Shipper", '#tabs-1') ?></li>
		<li><?php echo CHtml::link("Consignee", '#tabs-2') ?></li>
		<li><?php echo CHtml::link("Shipment", '#tabs-3') ?></li>
		<li><?php echo CHtml::link("Pick Up Details", '#tabs-4') ?></li>
	</ul>
	<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'pickup-form',
		'enableAjaxValidation' => false,
		'enableClientValidation' => true,
		'clientOptions' => array('validateOnSubmit' => true)
			));

	?>
	<div class="form">
		<div id="tabs-1">
			<div class="row">
				<?php echo $form->labelEx($inquiry, 'customer_account'); ?>
				<?php echo $form->textField($inquiry, 'customer_account', array('disabled' => 'disabled')) ?>
			</div>

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
						<?php echo $form->labelEx($shipment, 'shipper_province') ?>
						<?php echo $form->textField($shipment, 'shipper_province', array('value' => $contact->province)) ?>
						<?php echo $form->error($shipment, 'shipper_province') ?>
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

					<div class="row">
						<?php echo $form->labelEx($shipment, 'shipper_fax') ?>
						<?php echo $form->textField($shipment, 'shipper_fax', array('value' => $contact->fax)) ?>
						<?php echo $form->error($shipment, 'shipper_fax') ?>
					</div>

					<div class="row">
						<?php echo $form->labelEx($shipment, 'shipper_facebook') ?>
						<?php echo $form->textField($shipment, 'shipper_facebook', array('value' => $contact->facebook)) ?>
						<?php echo $form->error($shipment, 'shipper_facebook') ?>
					</div>

					<div class="row">
						<?php echo $form->labelEx($shipment, 'shipper_twitter') ?>
						<?php echo $form->textField($shipment, 'shipper_twitter', array('value' => $contact->twitter)) ?>
						<?php echo $form->error($shipment, 'shipper_twitter') ?>
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
						<?php echo $form->labelEx($shipment, 'receiver_province') ?>
						<?php echo $form->textField($shipment, 'receiver_province') ?>
						<?php echo $form->error($shipment, 'receiver_province') ?>
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

					<div class="row">
						<?php echo $form->labelEx($shipment, 'receiver_fax') ?>
						<?php echo $form->textField($shipment, 'receiver_fax') ?>
						<?php echo $form->error($shipment, 'receiver_fax') ?>
					</div>

					<div class="row">
						<?php echo $form->labelEx($shipment, 'receiver_facebook') ?>
						<?php echo $form->textField($shipment, 'receiver_facebook') ?>
						<?php echo $form->error($shipment, 'receiver_facebook') ?>
					</div>

					<div class="row">
						<?php echo $form->labelEx($shipment, 'receiver_twitter') ?>
						<?php echo $form->textField($shipment, 'receiver_twitter') ?>
						<?php echo $form->error($shipment, 'receiver_twitter') ?>
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
				<?php echo $form->labelEx($shipment, 'pickup_date') ?>
				<?php echo $form->textField($shipment, 'pickup_date', array('value' => $inquiry->pickup_date)) ?>
				<?php echo $form->error($shipment, 'pickup_date') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'type') ?>
				<?php echo $form->DropdownList($shipment, 'type', $shipment->listtype, array('prompt' => 'select type')) ?>
				<?php echo $form->error($shipment, 'type') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($shipment, 'service_id') ?>
				<?php echo $form->DropdownList($shipment, 'service_id', CHtml::listData($services, 'id', 'name', $group), array('prompt' => 'Select Service')) ?>
				<?php if ($inquiry->service_type == 'city'): ?>
					<?php echo $form->DropdownList($inquiry, 'intra_city_area', CHtml::listData($intraCityArea, 'id', 'name'), array('prompt' => 'Select Area')) ?>
				<?php endif; ?>
				<?php echo $form->error($shipment, 'type') ?>
			</div>

			<h2>Items Detail</h2>
			<div class="item-head">
				<div class="i-title">Name</div>
				<div class="i-value">Value</div>
				<div class="i-weight">Weight (kg)</div>
				<div class="i-length">Length (cm)</div>
				<div class="i-width">Width (cm)</div>
				<div class="i-height">Height (cm)</div>
				<div class="i-volmat">Volume Metric (kg)</div>
				<div class="clearfix"></div>
			</div>
			<div id="items">
				<?php for ($i = 0; $i < count($items); $i++): ?>
					<?php
					$this->renderPartial('_item', array(
						'item' => $items[$i],
						'index' => $i,
					))

					?>
<?php endfor; ?>
			</div>
			<div class="clearfix"></div>
<?php echo CHtml::button('Add Item', array('id' => 'add-item')) ?>

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

			<div class="row">
				<?php echo $form->labelEx($shipment, 'special_service') ?>
				<?php echo $form->dropDownList($shipment, 'special_service', $shipment->listspecialservice, array('prompt' => 'select special service')) ?>
<?php echo $form->error($shipment, 'special_service') ?>
			</div>

		</div>
		<div id="tabs-4">

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
				<?php echo $form->textArea($shipment, 'pickup_address', array('value' => $contact->address)) ?>
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
						<?php echo $form->textField($shipment, 'pickup_postal', array('value' => $contact->postal)) ?>
<?php echo $form->error($shipment, 'pickup_postal') ?>
					</div>

					<div class="row">
						<?php echo $form->labelEx($shipment, 'pickup_fax') ?>
						<?php echo $form->textField($shipment, 'pickup_fax', array('value' => $contact->fax)) ?>
<?php echo $form->error($shipment, 'pickup_fax') ?>
					</div>

					<div class="row">
						<?php echo $form->labelEx($shipment, 'office_close_time') ?>
						<?php echo $form->DropdownList($shipment, 'office_close_time', $shipment->listtime(), array('prompt' => 'select time')) ?>
<?php echo $form->error($shipment, 'office_close_time') ?>
					</div>

					<div class="row">
						<?php echo $form->labelEx($shipment, 'shipment_ready_time') ?>
						<?php echo $form->DropdownList($shipment, 'shipment_ready_time', $shipment->listtime(), array('prompt' => 'select time')) ?>
<?php echo $form->error($shipment, 'shipment_ready_time') ?>
					</div>
				</div>
			</div>
			<div class="row">
				<?php echo $form->labelEx($shipment, 'pickup_note') ?>
				<?php echo $form->textArea($shipment, 'pickup_fax') ?>
<?php echo $form->error($shipment, 'pickup_fax') ?>
			</div>

		</div>
		<div class="row">
		<?php echo CHtml::submitButton($shipment->isNewRecord ? 'Create' : 'Save'); ?>
		</div>
<?php $this->endWidget(); ?>
	</div>
</div>
<?php Yii::app()->clientScript->registerCoreScript("jquery") ?>
<script>
	$("#add-item").click(function(){
		$.ajax({
			success: function(html){
				$("#items").append(html);
			},
			type: 'get',
			url: '<?php echo $this->createUrl('item') ?>',
			data: {
				index: $("#items .item").size()
			},
			cache: false,
			dataType: 'html'
		});
	});
</script>
