<?php $form=$this->beginWidget('CActiveForm',array(
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>false,
	)
)); ?>
<div class="row">
<?php echo $form->labelEx($model,'service_type'); ?>
	<ul>
<?php 
echo $form->radioButtonList($model,'service_type',$model->Service_type,array(
	'template' => '<li>{input}{label}</li>',
	'separator'=>'',
)); ?>
	</ul>
<?php echo $form->error($model,'service_type',array('inputContainer'=>'ul')); ?>
</div>


<?php echo CHtml::submitButton(Yii::t('web','Ok')) ?>

<?php $this->endWidget(); ?>
