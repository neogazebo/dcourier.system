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
		<?php echo $form->labelEx($rekening, 'bank'); ?>
		<?php echo $form->textField($rekening, 'bank'); ?>
		<?php echo $form->error($rekening, 'bank'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($rekening, 'cabang'); ?>
		<?php echo $form->textField($rekening, 'cabang'); ?>
		<?php echo $form->error($rekening, 'cabang'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($rekening, 'rekening'); ?>
		<?php echo $form->textField($rekening, 'rekening'); ?>
		<?php echo $form->error($rekening, 'rekening'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($rekening, 'nama'); ?>
		<?php echo $form->textField($rekening, 'nama'); ?>
		<?php echo $form->error($rekening, 'nama'); ?>
	</div>
	
	<?php echo $form->hiddenField($rekening,'customer_id',array('value' => $customer->id)) ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($rekening->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div>