<div class="form">

	<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'import-area-code',
		'enableAjaxValidation' => false,
		'method' => 'post',
		'htmlOptions' => array(
			'enctype' => 'multipart/form-data'
		)
			));

	?>

	<fieldset>
		<legend>
			<p class="note">Fields with <span class="required">*</span> are required.</p>
		</legend>
		
		<div class="row">
			<?php echo $form->labelEx($model, 'file'); ?>
			<?php echo $form->fileField($model, 'file'); ?>
			<?php echo $form->error($model, 'file'); ?>
		</div>

		<div class="row buttons">
			<?php echo CHtml::submitButton('Upload'); ?>
		</div>

	</fieldset>

	<?php $this->endWidget(); ?>

</div><!-- form -->