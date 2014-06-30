<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'international-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'service_id'); ?>
		<?php echo $form->dropDownList($model,'service_id',  CHtml::listData(InternationalCompanyService::model()->findAll(), 'id', 'name'),array('prompt' => '-- select service --')); ?>
		<?php echo $form->error($model,'service_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model,'type',$model->type(),array('prompt' => '-- select type --')); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'weight'); ?>
		<?php echo $form->textField($model,'weight',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'weight'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'zone_a'); ?>
		<?php echo $form->textField($model,'zone_a',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'zone_a'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'zone_b'); ?>
		<?php echo $form->textField($model,'zone_b',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'zone_b'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'zone_c'); ?>
		<?php echo $form->textField($model,'zone_c',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'zone_c'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'zone_d'); ?>
		<?php echo $form->textField($model,'zone_d',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'zone_d'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'zone_e'); ?>
		<?php echo $form->textField($model,'zone_e',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'zone_e'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'zone_f'); ?>
		<?php echo $form->textField($model,'zone_f',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'zone_f'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'zone_g'); ?>
		<?php echo $form->textField($model,'zone_g',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'zone_g'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->