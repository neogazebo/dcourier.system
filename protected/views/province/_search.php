<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

<div class="row">
		<?php echo $form->label($model, 'name'); ?>
		<?php
		$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'name' => 'Province[name]',
				'source' => $this->createUrl('province/autocomplete'),
				// additional javascript options for the autocomplete plugin
				'options' => array(
						'showAnim' => 'fold',
				),
				'htmlOptions'=>array('value'=>$model->name)
		));
		?>
	</div>
	

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->