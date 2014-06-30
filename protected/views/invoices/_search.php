<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tgl_terbit'); ?>
		<?php echo $form->textField($model,'tgl_terbit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tgl_pembayaran'); ?>
		<?php echo $form->textField($model,'tgl_pembayaran'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tgl_jatuh_tempo'); ?>
		<?php echo $form->textField($model,'tgl_jatuh_tempo'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'customer_id'); ?>
		<?php echo $form->textField($model,'customer_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->