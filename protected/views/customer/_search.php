<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl('customer/admin'),
	'method'=>'get',
)); ?>

	<fieldset>
		<legend>Search</legend>
		<div class="row">
			<?php echo $form->label($model,'name'); ?>
			<?php echo $form->textField($model,'name'); ?>
		</div>
		<div class="row">
			<?php echo $form->label($model,'accountnr'); ?>
			<?php echo $form->textField($model,'accountnr'); ?>
		</div>
		<div class="row">
			<?php echo $form->label($model,'search_phone'); ?>
			<?php echo $form->textField($model,'search_phone'); ?>
		</div>
		<div class="row buttons">
			<?php echo CHtml::submitButton('Search'); ?>
		</div>
	</fieldset>	
	

<?php $this->endWidget(); ?>

</div><!-- search-form -->