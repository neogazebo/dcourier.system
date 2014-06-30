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
		'id' => 'rate-company-form',
		'enableAjaxValidation' => false,
			));

	?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($new_event); ?>

	<div class="subcolumns">
		<div class="c50l">
			<div class="row">
				<?php echo $form->labelEx($new_event, 'shipment_list'); ?>
				<?php echo $form->textArea($new_event, 'shipment_list',array('cols' => 40, 'rows' => 5)); ?>
				<?php echo $form->error($new_event, 'shipment_list'); ?>
			</div>
			
			<div class="row">
				<?php echo $form->labelEx($new_event, 'title'); ?>
				<?php echo $form->textField($new_event, 'title'); ?>
				<?php echo $form->error($new_event, 'title'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($new_event, 'description'); ?>
				<?php echo $form->textArea($new_event, 'description', array('cols' => 40, 'rows' => 5)); ?>
				<?php echo $form->error($new_event, 'description'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($new_event, 'event_time'); ?>
				<?php
				$this->widget('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker', array(
					'attribute' => 'event_time',
					'model' => $new_event,
					'language' => '',
					'options' => array(
						'yearRange' => '-0:+7',
						'changeYear' => 'true',
						'changeMonth' => 'true',
					),
				));

				?>
				<?php echo $form->error($new_event, 'event_time'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($new_event, 'last status'); ?>
				<?php echo $form->dropDownList($new_event, 'status', CHtml::listData(ShipmentStatus::model()->findAll(), 'id', 'status')); ?>
				<?php echo $form->error($new_event, 'status'); ?>
			</div>

		</div>
		<div class="c50r">

			<div id="rm">	
			</div>

		</div>
	</div>

	<div class="row buttons">
		<?php echo $form->hiddenField($new_event, 'user_id', array('value' => Yii::app()->user->id)) ?>
		<?php echo CHtml::submitButton('Update'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
$ajax = CHtml::ajax(array(
	'url' => array('selectRemarks'),
	'dataType' => 'html',
	'type' => 'post',
	'data' => 'js: {status_id:$(this).val()} ',
	'success' => 'function(data){
		$("#rm").children().remove();
		$("#rm").append(data);
		return true;
	}'
));
$cs = Yii::app()->clientScript;
$script = <<<SCRIPT
    $('#ShipmentEvent_status').live('change',function(){ $ajax });
SCRIPT;
$cs->registerScript('change_status', $script);
?>