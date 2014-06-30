<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'service-detail-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'product_service_id'); ?>
		<?php echo $form->dropDownList($model,'product_service_id',CHtml::listData($availableProductServices, 'id', 'code'),array('prompt'=>'')); ?>
		<?php echo $form->error($model,'product_service_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->