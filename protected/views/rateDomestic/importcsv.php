<div class="form">

	<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'service-form',
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
			<?php echo $form->labelEx($model, 'origin_id'); ?>
			<?php echo $form->dropDownList($model, 'origin_id',  CHtml::listData(Origins::model()->findAll(), 'id','name'),array('prompt' => '--')); ?>
			<?php echo $form->error($model, 'origin_id'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model, 'service_id'); ?>
			<?php echo $form->dropDownList($model, 'service_id',  CHtml::listData(RateCompanyService::model()->findAll(), 'id','name','rateCompany.name'),array('prompt' => '--')); ?>
			<?php echo $form->error($model, 'service_id'); ?>
		</div>
		
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