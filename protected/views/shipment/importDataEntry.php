<?php
$flashMessages = Yii::app()->user->getFlashes();
if ($flashMessages)
{
	foreach ($flashMessages as $key => $message)
	{
		echo '<div class="flash-' . $key . '">' . $message . "</div>";
	}
}
?>
<div class="form">

	<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'import-order',
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
			<?php echo $form->labelEx($model, 'customer_account'); ?>
			<?php echo $form->textField($model, 'customer_account'); ?>
			<?php echo $form->error($model, 'customer_account'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model, 'file'); ?>
			<?php echo $form->fileField($model, 'file'); ?>
			<?php echo $form->error($model, 'file'); ?>
		</div>

		<div class="row buttons">
			<?php echo CHtml::submitButton('Create'); ?>
		</div>

	</fieldset>

	<?php $this->endWidget(); ?>

</div><!-- form -->