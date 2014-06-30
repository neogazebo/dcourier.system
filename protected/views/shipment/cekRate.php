<?php
$this->breadcrumbs = array(
	'Customer Service' => array('shipment/customerService'),
	'Cek Rate',
);

$this->menu = array(
//	array('label'=>'Manage Product', 'url'=>array('index')),
);

?>

<?php
$sugggestShipperPostal = CHtml::ajax(array(
			'url' => array('shipment/suggestPostal'),
			'dataType' => 'json',
			'data' => array(
				'district' => "js: $('#InquiryForm_shipper_city').val()",
				'term' => "js:request.term"
			),
			'success' => 'js:function(data){
										realData=$.makeArray(data);
											response($.map(realData, function (item){
												return{
													value:item.value,
													label:item.label
												}
											}))
									}'
				)
);
$sugggestReceiverPostal = CHtml::ajax(array(
			'url' => array('shipment/suggestPostal'),
			'dataType' => 'json',
			'data' => array(
				'district' => "js: $('#InquiryForm_receiver_city').val()",
				'term' => "js:request.term"
			),
			'success' => 'js:function(data){
										realData=$.makeArray(data);
											response($.map(realData, function (item){
												return{
													value:item.value,
													label:item.label
												}
											}))
									}'
				)
);

$cekRates = CHtml::ajax(array(
		'url' => array('shipment/getRates'),
		'dataType' => 'html',
		'update' => '#rates_container',
		'data' => array(
			'Cek[receiver_country]' => "js:$('#InquiryForm_receiver_country').val()",
			'Cek[receiver_postal]' => "js:$('#InquiryForm_receiver_postal').val()",
			'Cek[package_weight]' => "js:$('#InquiryForm_package_weight').val()",
			'customer_id' => "js:$('#Customer_id').val()",
		),
	)
);

?>

<h1>Cek Rate</h1>

<div class="form">
	<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'cekRate-form',
		'enableAjaxValidation' => false,
		'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'validateOnChange' => false,
			'afterValidate' => 'js:cekRate'
			)
		));

	?>

<!--	<div class="row">
		<?php // echo $form->labelEx($customer, 'accountnr'); ?>
		<?php // echo $form->textField($customer, 'accountnr'); ?>
		<?php // echo $form->hiddenField($customer, 'id'); ?>
		<?php // echo $form->error($customer, 'accountnr'); ?>
	</div>-->
	<div class="row">
				<?php echo $form->labelEx($customer, 'accountnr') ?>
				<?php
				$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					'model' => $customer,
					'htmlOptions'=>array('size'=>40),
					'attribute' => 'accountnr',
					'sourceUrl' => array('booking/suggestCustomer','mode'=>'accountnr'),
					'options' => array(
						'select' => 'js:function(event,ui){
													$("#Customer_id").val(ui.item.id);
													return true;
												}',
						)
					)
				);
				?>
				<?php echo $form->hiddenField($customer,'id') ?>
			</div>
	<div class="subcolumns">
		<div class="c48l">

			<div class="row">
				<?php echo $form->labelEx($inquiry, 'shipper_country'); ?>
				<?php echo $form->textField($inquiry, 'shipper_country'); ?>
				<?php echo $form->error($inquiry, 'shipper_country'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($inquiry, 'shipper_city'); ?>
				<?php
				$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					'model' => $inquiry,
					'attribute' => 'shipper_city',
					'sourceUrl' => array('shipment/suggestDistrict'),
				));

				?>
<?php echo $form->error($inquiry, 'shipper_city'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($inquiry, 'shipper_postal'); ?>
				<?php
				$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					'htmlOptions' => array('maxlength' => 5),
					'model' => $inquiry,
					'attribute' => 'shipper_postal',
					'source' => "js:function(request,response){{$sugggestShipperPostal}}",
						)
				);

				?>
<?php echo $form->error($inquiry, 'shipper_postal'); ?>
			</div>

		</div>
		<div class="c48r">

			<div class="row">
<?php echo $form->labelEx($inquiry, 'receiver_country'); ?>
<?php echo $form->textField($inquiry, 'receiver_country'); ?>
				<?php echo $form->error($inquiry, 'receiver_country'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($inquiry, 'receiver_city'); ?>
				<?php
				$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					'model' => $inquiry,
					'attribute' => 'receiver_city',
					'sourceUrl' => array('shipment/suggestDistrict'),
				));

				?>
				<?php echo $form->error($inquiry, 'receiver_city'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($inquiry, 'receiver_postal'); ?>
				<?php
				$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					'htmlOptions' => array('maxlength' => 5),
					'model' => $inquiry,
					'attribute' => 'receiver_postal',
					'source' => "js:function(request,response){{$sugggestReceiverPostal}}",
						)
				);

				?>
		<?php echo $form->error($inquiry, 'receiver_postal'); ?>
			</div>

		</div>
	</div>
	<div class="row">
		<?php echo $form->labelEx($inquiry, 'package_weight'); ?>
<?php echo $form->textField($inquiry, 'package_weight', array('maxlength' => 5, 'size' => 3)); ?>
	<?php echo $form->error($inquiry, 'package_weight'); ?>
	</div>

	<div class="row buttons">
	<?php echo CHtml::submitButton('Cek Rate'); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- end form -->

<div id="rates_container"></div>

<?php 
	$cs = Yii::app()->clientScript;
	$getRates = <<<EOD
function cekRate(form, data, hasError){
	if(!hasError)
	{{$cekRates}}
}
EOD;
$cs->registerScript('get_rates', $getRates);
?>