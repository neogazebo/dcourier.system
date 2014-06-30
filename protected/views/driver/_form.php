<?php
/* @var $this DriverController */
/* @var $model Driver */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'driver-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->dropDownList($model,'user_id',  CHtml::listData(User::model()->findAll('id != 0'), 'id', 'username'),array('prompt' => '')); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'routing_code'); ?>
		<?php echo $form->dropDownList($model,'routing_code',CHtml::listData(IntraCityRouting::getRoutingCode(),'code','code'),array('prompt' => '')); ?>
		<?php echo $form->error($model,'routing_code'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->