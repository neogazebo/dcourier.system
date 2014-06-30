<?php $form=$this->beginWidget('CActiveForm'); ?>
<div class="row">
<?php echo $form->labelEx($model,'service_type'); ?>
	<br />
<?php echo $form->radioButtonList($model,'service_type',$model->Service_type); ?>
<?php echo $form->error($model,'service_type'); ?>
</div>


<?php echo CHtml::submitButton(Yii::t('web','Ok')) ?>

<?php $this->endWidget(); ?>
