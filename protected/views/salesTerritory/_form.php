<?php
/* @var $this SalesTeritoryController */
/* @var $model SalesTeritory */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sales-teritory-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->dropDownList($model,'user_id',  CHtml::listData(User::model()->findAll('id!=0'), 'id', 'username')); ?>
		<?php echo $form->error($model,'users_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'territory'); ?>
		<?php echo $form->textField($model,'territory',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'territory'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->