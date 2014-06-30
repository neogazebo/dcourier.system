<div class="form">
	<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'customer-form',
		'enableAjaxValidation' => false,
			));

	?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php // echo $form->errorSummary($model);  ?>
	<!-- begin contact form -->
	
	<div class="row">
		<?php echo $form->labelEx($contact, 'full_name'); ?>
		<?php echo $form->textField($contact, 'full_name', array('size' => 30, 'maxlength' => 80)); ?>
		<?php echo $form->error($contact, 'full_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($contact, 'email'); ?>
		<?php echo $form->textField($contact, 'email', array('size' => 30, 'maxlength' => 80)); ?>
		<?php echo $form->error($contact, 'email'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($contact, 'address'); ?>
		<?php echo $form->textArea($contact, 'address', array('rows' => 2, 'cols' => 25)); ?>
		<?php echo $form->error($contact, 'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($contact, 'phone1'); ?>
		<?php echo $form->textField($contact, 'phone1', array('size' => 30, 'maxlength' => 80)); ?>
		<?php echo $form->error($contact, 'phone1'); ?>
	</div>
	<?php echo $form->hiddenField($customerContact, 'customer_id') ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($customerContact->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div>