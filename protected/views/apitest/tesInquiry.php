<div class="form">

	<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'inquiry-form-tesInquiry-form',
		'enableAjaxValidation' => false,
		'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'validateOnChange' => false,
			'afterValidate' => 'js:valideteFancy'
		)
	));

	?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'receiver_country'); ?>
		<?php echo $form->textField($model, 'receiver_country'); ?>
		<?php echo $form->error($model, 'receiver_country'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'receiver_city'); ?>
		<?php echo $form->textField($model, 'receiver_city'); ?>
		<?php echo $form->error($model, 'receiver_city'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'receiver_postal'); ?>
		<?php echo $form->textField($model, 'receiver_postal'); ?>
		<?php echo $form->error($model, 'receiver_postal'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'shipper_country'); ?>
		<?php echo $form->textField($model, 'shipper_country'); ?>
		<?php echo $form->error($model, 'shipper_country'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'shipper_city'); ?>
		<?php echo $form->textField($model, 'shipper_city'); ?>
		<?php echo $form->error($model, 'shipper_city'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'shipper_postal'); ?>
		<?php echo $form->textField($model, 'shipper_postal'); ?>
		<?php echo $form->error($model, 'shipper_postal'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
$getRates = <<<EOD

function valideteFancy(form, data, hasError){
		if(!hasError){
			alert("tes");
		}
}
EOD;
$cs = Yii::app()->clientScript;
$cs->registerScript('change_service_list', $getRates);

?>