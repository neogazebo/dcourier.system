<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'rate-company-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->hiddenField($model,'id'); ?>
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'is_city'); ?>
		<?php echo $form->checkBox($model,'is_city'); ?>
		<?php echo $form->error($model,'is_city'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'is_domestic'); ?>
		<?php echo $form->checkBox($model,'is_domestic'); ?>
		<?php echo $form->error($model,'is_domestic'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'is_international'); ?>
		<?php echo $form->checkBox($model,'is_international'); ?>
		<?php echo $form->error($model,'is_international'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->