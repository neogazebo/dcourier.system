<div class="form">

	<?php
	$form = $this->beginWidget('CActiveForm', array(
			'id' => 'userProfile-form',
			'enableAjaxValidation' => true,
					));
	?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>
	
	<?php echo $form->errorSummary($model); ?>
	<div>
			<h1>Login Info:</h1>
			<br>
			<div class="row">
					<?php echo $form->labelEx($model,'username'); ?>
					<?php echo $form->textField($model,'username'); ?>
					<?php echo $form->error($model,'username'); ?>
			</div>
			<br>
			<h5>Kata Sandi</h5>
			<div class="row">
					<?php echo $form->labelEx($model,'oldPassword'); ?>
					<?php echo $form->passwordField($model,'oldPassword'); ?>
					<?php echo $form->error($model,'oldPassword'); ?>
			</div>
			<div class="row">
					<?php echo $form->labelEx($model,'newPassword'); ?>
					<?php echo $form->passwordField($model,'newPassword'); ?>
					<?php echo $form->error($model,'newPassword'); ?>
			</div>
			<div class="row">
					<?php echo $form->labelEx($model,'confirmPassword'); ?>
					<?php echo $form->passwordField($model,'confirmPassword'); ?>
					<?php echo $form->error($model,'confirmPassword'); ?>
			</div>
			<br>
			<div class="row">
					<?php echo $form->labelEx($model,'email'); ?>
					<?php echo $form->textField($model,'email'); ?>
					<?php echo $form->error($model,'email'); ?>
			</div>
	</div>
	<div>
			<h1>Detail Info:</h1>
			<div class="row">
					<?php echo $form->labelEx($model,'firstname'); ?>
					<?php echo $form->textField($model,'firstname'); ?>
					<?php echo $form->error($model,'firstname'); ?>
			</div>			
			<div class="row">
					<?php echo $form->labelEx($model,'lastname'); ?>
					<?php echo $form->textField($model,'lastname'); ?>
					<?php echo $form->error($model,'lastname'); ?>
			</div>			

			<div class="row">
					<?php echo $form->labelEx($model,'telp_home'); ?>
					<?php echo $form->textField($model,'telp_home'); ?>
					<?php echo $form->error($model,'telp_home'); ?>
			</div>			
			<div class="row">
					<?php echo $form->labelEx($model,'telp_office'); ?>
					<?php echo $form->textField($model,'telp_office'); ?>
					<?php echo $form->error($model,'telp_office'); ?>
			</div>			
			<div class="row">
					<?php echo $form->labelEx($model,'timezone'); ?>
					<?php echo $form->dropDownList($model,'timezone',$model->listTimeZone(),array(
							
					)); ?>
					<?php echo $form->error($model,'timezone'); ?>
			</div>			
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('save'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->