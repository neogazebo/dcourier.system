<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pickup_date'); ?>
		<?php echo $form->textField($model,'pickup_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shipment_id'); ?>
		<?php echo $form->textField($model,'shipment_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'driver_id'); ?>
		<?php echo $form->textField($model,'driver_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->