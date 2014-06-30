<div class="wide form">

	<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'booking-form',
		'enableAjaxValidation' => false,
			));

	?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="subcolumns">
		<div class="c50l">
			<div class="row">
				<?php echo $form->labelEx($model, 'booking_code'); ?>
				<?php echo $form->textField($model, 'booking_code', array('disabled' => 'disabled')); ?>
				<?php echo $form->error($model, 'booking_code'); ?>
			</div>
			
			<div class="row">
				<?php echo CHtml::label('Waybill','awb') ?>
				<?php echo CHtml::textField('awb',$awb,array('disabled' => 'disabled')); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model, 'customer_account') ?>
				<?php
				$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					'model' => $model,
					'htmlOptions'=>array('size'=>40),
					'attribute' => 'customer_account',
					'sourceUrl' => array('booking/suggestCustomer','mode'=>'accountnr'),
					'options' => array(
						'select' => 'js:function(event,ui){
													$("#Booking_customer_id").val(ui.item.id);
													return true;
												}',
						)
					)
				);
				?>
				<?php echo $form->hiddenField($model,'customer_id') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model, 'request_by'); ?>
				<?php echo $form->textField($model, 'request_by'); ?>
				<?php echo $form->error($model, 'request_by'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model, 'address'); ?>
				<?php echo $form->textArea($model, 'address',array('rows' => 6, 'cols' => 50)); ?>
				<?php echo $form->error($model, 'address'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model, 'city'); ?>
				<?php echo $form->textField($model, 'city'); ?>
				<?php echo $form->error($model, 'city'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model, 'postal'); ?>
				<?php echo $form->textField($model, 'postal'); ?>
				<?php echo $form->error($model, 'postal'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model, 'country'); ?>
				<?php echo $form->textField($model, 'country'); ?>
				<?php echo $form->error($model, 'country'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model, 'phone'); ?>
				<?php echo $form->textField($model, 'phone'); ?>
				<?php echo $form->error($model, 'phone'); ?>
			</div>
		</div>
		<div class="c50r"></div>
	</div>

	<div class="subcolumns">
		<div class="c50l">
			<div class="row">
				<?php echo $form->labelEx($model, 'pickup_date'); ?>
				<?php
						$this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'attribute' => 'pickup_date',
							'model' => $model,
							'options' => array(
								'yearRange' => '-1:+2',
								'changeYear' => 'true',
								'changeMonth' => 'true',
							),
						));
				?>
				<?php echo $form->error($model, 'pickup_date'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model, 'shipment_ready_time'); ?>
				<?php echo $form->textField($model, 'shipment_ready_time',array('size'=>5)); ?>
				<?php echo $form->error($model, 'shipment_ready_time'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model, 'office_close_time'); ?>
				<?php echo $form->textField($model, 'office_close_time',array('size'=>5)); ?>
				<?php echo $form->error($model, 'office_close_time'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model, 'address_type'); ?>
				<?php echo $form->dropDownList($model, 'address_type', Booking::model()->list_address_type, array('prompt' => '')); ?>
				<?php echo $form->error($model, 'address_type'); ?>
			</div>
		</div>
		<div class="c50r">
			<div class="row">
				<?php echo $form->labelEx($model, 'pickup_note'); ?>
				<?php echo $form->textArea($model, 'pickup_note', array('rows' => 6, 'cols' => 50)); ?>
				<?php echo $form->error($model, 'pickup_note'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model, 'shipment_location'); ?>
				<?php echo $form->textField($model, 'shipment_location'); ?>
				<?php echo $form->error($model, 'shipment_location'); ?>
			</div>
		</div>
	</div>
	<div class="row">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
	<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery-timepicker/jquery.timepicker.min.js');
$cs->registerCssFile(Yii::app()->baseUrl.'/js/jquery-timepicker/jquery.timepicker.css');
$time_picker = <<<EOF
	$("#Booking_office_close_time,#Booking_shipment_ready_time").timepicker({ 'timeFormat': 'H:i' });
EOF;
$cs->registerScript('office_close_time',$time_picker);