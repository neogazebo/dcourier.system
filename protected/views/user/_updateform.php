<div class="form">

	<?php
	$form = $this->beginWidget('CActiveForm', array(
			'id' => 'user-form',
			'enableAjaxValidation' => true,
					));
	?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<table>
		<tr><td>
				<h1>Login Info</h1>
				<div class="row">
                <?php echo $form->hiddenField($model,'id') ?>
			<?php echo $form->labelEx($model,'username'); ?>
			<?php echo $form->textField($model,'username',array('size'=>30,'maxlength'=>45)); ?>
			<?php echo $form->error($model,'username'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model, 'password'); ?>
					<?php echo $form->passwordField($model, 'password', array('size' => 30, 'maxlength' => 80, 'autocomplete'=> 'off')); ?>
					<?php echo $form->error($model, 'password'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model, 'confirmPassword'); ?>
					<?php echo $form->passwordField($model, 'confirmPassword', array('size' => 30, 'maxlength' => 80)); ?>
					<?php echo $form->error($model, 'confirmPassword'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model, 'email'); ?>
					<?php echo $form->textField($model, 'email', array('size' => 30, 'maxlength' => 255)); ?>
					<?php echo $form->error($model, 'email'); ?>
				</div>
			</td><td>
				<h1>Detail Info</h1>
				<div class="row">
					<?php echo $form->labelEx($model, 'firstname'); ?>
					<?php echo $form->textField($model, 'firstname', array('size' => 30, 'maxlength' => 45)); ?>
					<?php echo $form->error($model, 'firstname'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model, 'lastname'); ?>
					<?php echo $form->textField($model, 'lastname', array('size' => 30, 'maxlength' => 45)); ?>
					<?php echo $form->error($model, 'lastname'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model, 'nip'); ?>
					<?php echo $form->textField($model, 'nip', array('size' => 30, 'maxlength' => 45)); ?>
					<?php echo $form->error($model, 'nip'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model, 'telp_home'); ?>
					<?php echo $form->textField($model, 'telp_home', array('size' => 30, 'maxlength' => 45)); ?>
					<?php echo $form->error($model, 'telp_home'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model, 'telp_office'); ?>
					<?php echo $form->textField($model, 'telp_office', array('size' => 30, 'maxlength' => 45)); ?>
					<?php echo $form->error($model, 'telp_office'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model, 'timezone'); ?>
					<?php echo $form->dropDownList($model, 'timezone', $model->listTimeZone()); ?>
					<?php echo $form->error($model, 'timezone'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model, 'active'); ?>
					<?php echo $form->dropDownList($model, 'active', $model->activeList()); ?>
					<?php echo $form->error($model, 'active'); ?>
				</div>
			</td></tr></table>

	<div class="row buttons">
		<?php echo CHtml::ajaxSubmitButton('save', array('user/update'), array('update' => '#id_view')); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->