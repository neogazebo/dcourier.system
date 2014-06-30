<?php
/* @var $this ApiAccesslistController */
/* @var $model ApiAccesslist */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'api-accesslist-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
    <br>
	<div class="row">
		<?php echo $form->hiddenField($model,'customer_id',array('value' => $customer_id)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ip_address'); ?>
		<?php echo $form->textField($model,'ip_address',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'ip_address'); ?>
	</div>

	<div class="row">
		<?php echo $form->hiddenField($model,'user_id',array('value' => Yii::app()->user->getId())) ?>
	</div>
        
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->