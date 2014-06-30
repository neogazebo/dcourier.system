<fieldset>
	<legend>Shipping Rates</legend>
	<div class="row">
		<?php echo $form->labelEx($inquiry, 'freight_charges') ?>
		<?php echo $form->textField($inquiry, 'freight_charges') ?>
		<?php echo $form->error($inquiry, 'freight_charges') ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($inquiry, 'fuel_charges') ?>
		<?php echo $form->textField($inquiry, 'fuel_charges') ?>
		<?php echo CHtml::textField('percent_fuel', 24,array('id' => 'percent_fuel','size' => 3)) ?> %
		<?php echo $form->error($inquiry, 'fuel_charges') ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($inquiry, 'vat') ?>
		<?php echo $form->textField($inquiry, 'vat') ?>
		<?php echo $form->error($inquiry, 'vat') ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($inquiry, 'shipping_charges') ?>
		<?php echo $form->textField($inquiry, 'shipping_charges',array('class'=>'calculate')) ?>
		<?php echo $form->error($inquiry, 'shipping_charges') ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($inquiry, 'cod') ?>
		<?php echo $form->textField($inquiry, 'cod',array('class' => 'calculate')) ?>
		<?php echo $form->error($inquiry, 'cod') ?>
	</div>

	<h2>Additional Cost</h2>
	<div class="cost-head">
		<div class="c-name">Cost Name</div>
		<div class="c-desc">Description</div>
		<div class="c-cost">Cost(Rp)</div>
		<div class="clearfix"></div>
	</div>
	<div id="costs">
		<?php for ($i = 0; $i < count($costs); $i++): ?>
			<?php
			$this->renderPartial('_cost', array(
				'cost' => $costs[$i],
				'index' => $i,
			))

			?>
		<?php endfor; ?>
	</div>
	<div class="clearfix"></div>
	<?php echo CHtml::button('Add Cost', array('id' => 'add-cost')) ?>
	
	<div class="row">
		<?php echo $form->labelEx($shipment, 'charges') ?>
		<?php echo $form->textField($shipment, 'charges',array('disabled'=>'disabled')) ?>
	</div>
</fieldset>
<div class="row buttons">
	<?php echo CHtml::button('Next', array('id' => 'nxt')) ?>
	<?php // echo CHtml::button('Back',array('id' => 'back2')) ?>
</div>

<?php Yii::app()->clientScript->registerCoreScript("jquery") ?>
<script type="text/javascript">
	$("#add-cost").click(function(){
		$.ajax({
			success: function(html){
				$("#costs").append(html);
			},
			type: 'get',
			url: '<?php echo $this->createUrl('cost') ?>',
			data: {
				index: $("#costs .cost-container").size()
			},
			cache: false,
			dataType: 'html'
		});
	});
	
	$('#percent_fuel').live('change',function(){
		var freight_charges = parseFloat($('#InquiryForm_freight_charges').val());
		var fuel_charge = freight_charges * (parseFloat($(this).val()) / 100);
		var vat = (freight_charges + fuel_charge) /100;
		var ship_charge = vat + freight_charges;
		
		$('#InquiryForm_fuel_charges').val( fuel_charge );
		$('#InquiryForm_vat').val( vat );
		$('#InquiryForm_shipping_charges').val( ship_charge );
	});
</script>
<?php
$getTotalCost = CHtml::ajax(array(
		'method' => 'post',
		'dataType' => 'json',
		'url' => array('shipment/getTotalCharges'),
		'data' => 'js:$("input.calculate").serialize()',
		'success' => 'js:function(data){
			$("#Shipment_charges").val(data);
		}'
	));
$cs = Yii::app()->clientScript;
$hitung_total_charges = <<<EOD
$("#InquiryForm_cod").live('blur',function(){	$getTotalCost	});
$(".add_cost").live('blur',function(){	$getTotalCost	});
EOD;
$cs->registerScript('cod_change', $hitung_total_charges);

$back = <<<EOD
$("#back2").click(function(){
	$(".form #ship-rate").fadeOut("slow",function(){
				$(".form #ship-detail").fadeIn();
	});
});
EOD;
$cs->registerScript('back-to-inquirydetail', $back);
$passing_data = <<<EOD
$("#nxt").click(function(){
	var re_ccountry = $("#InquiryForm_receiver_country").val();
	var re_city = $("#InquiryForm_receiver_city").val();
	var re_postal = $("#InquiryForm_receiver_postal").val();
	
	$("#Shipment_receiver_country").val(re_ccountry);
	$("#Shipment_receiver_city").val(re_city);
	$("#Shipment_receiver_postal").val(re_postal);
	window.location = "#ship-detail";
});
EOD;
$cs->registerScript('pass-data',$passing_data);
?>