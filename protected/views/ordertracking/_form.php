<div class="form">

	<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'rate-company-form',
		'enableAjaxValidation' => false,
			));

	?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($new_event); ?>

	<div class="subcolumns">
		<div class="c50l">
			<div class="row">
				<?php echo $form->labelEx($new_event, 'title'); ?>
				<?php echo $form->textField($new_event, 'title'); ?>
				<?php echo $form->error($new_event, 'title'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($new_event, 'description'); ?>
				<?php echo $form->textArea($new_event, 'description', array('cols' => 40, 'rows' => 5)); ?>
				<?php echo $form->error($new_event, 'description'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($new_event, 'event_time'); ?>
				<?php
				$this->widget('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker', array(
					'attribute' => 'event_time',
					'model' => $new_event,
					'language' => '',
					'options' => array(
						'yearRange' => '-0:+7',
						'changeYear' => 'true',
						'changeMonth' => 'true',
					),
				));

				?>
				<?php echo $form->error($new_event, 'event_time'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($new_event, 'last status'); ?>
				<?php echo $form->dropDownList($new_event, 'status', CHtml::listData(ShipmentStatus::model()->findAll(), 'id', 'status')); ?>
				<?php echo $form->error($new_event, 'status'); ?>
			</div>

		</div>
		<div class="c50r">

			<div id="rm">
			<?php if ($shipment->shipping_remark != '' || !empty($shipment->shipping_remark)): ?>
				<div class="row">
						<?php echo $form->labelEx($new_event, 'last remark'); ?>
						<?php echo $form->dropDownList($new_event, 'remark', CHtml::listData(ShipmentRemark::model()->findAllByAttributes(array('status_id' => $shipment->shipping_status)), 'id', 'name')); ?>
						<?php echo $form->error($new_event, 'remark'); ?>
					</div>
				<?php endif; ?>
				<?php if ($shipment->shipping_status == 10): ?>
					<h4>Recipient Detail</h4>
					<div class="row">
						<?php echo $form->labelEx($new_event, 'recipient_name'); ?>
						<?php echo $form->textField($new_event, 'recipient_name'); ?>
						<?php echo $form->error($new_event, 'recipient_name'); ?>
					</div>
					<div class="row">
						<?php echo $form->labelEx($new_event, 'recipient_title'); ?>
						<?php echo $form->textField($new_event, 'recipient_title'); ?>
						<?php echo $form->error($new_event, 'recipient_title'); ?>
					</div>
					<div class="row">
						<?php echo $form->labelEx($new_event, 'recipient_date'); ?>
						<?php
						$this->widget('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker', array(
							'attribute' => 'recipient_date',
							'model' => $new_event,
							'language' => '',
							'options' => array(
								'yearRange' => '-0:+7',
								'changeYear' => 'true',
								'changeMonth' => 'true',
							),
						));

						?>
						<?php echo $form->error($new_event, 'recipient_date'); ?>
					</div>
				<?php endif; ?>
					
				
			</div>

		</div>
	</div>

	<div class="row buttons">
		<?php echo $form->hiddenField($new_event, 'user_id', array('value' => Yii::app()->user->id)) ?>
		<?php echo $form->hiddenField($new_event, 'shipment_id', array('value' => $shipment->id)) ?>
		<?php echo CHtml::submitButton('Update'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->