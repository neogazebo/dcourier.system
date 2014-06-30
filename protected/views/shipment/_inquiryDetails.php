<fieldset>
	<legend>Shipment Details</legend>
	
	<div class="subcolumns">
		<div class="c48l">
			<div class="row">
				<?php echo $form->labelEx($shipment, 'type') ?>
				<div id="status-radiobutton">
					<?php echo $form->radioButtonList($shipment, 'type', $shipment->listtype,array('class' => 'field_calc_charge')) ?>
				</div>
				<?php echo $form->error($shipment, 'type') ?>
			</div>
		</div>

		<div class="c48r">
			<div class="row">
				<?php echo $form->labelEx($inquiry, 'insurance') ?>
				<?php echo $form->checkBox($inquiry, 'insurance') ?>
				<?php echo $form->error($inquiry, 'insurance') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($inquiry, 'special_service') ?>
				<?php echo $form->dropDownList($inquiry, 'special_service',$inquiry->listspecialservice, array('prompt' => '--special service--')) ?>
				<?php echo $form->error($inquiry, 'special_service') ?>
			</div>
		</div>
	</div>
	
	<div class="row service_list">
		<?php echo $form->labelEx($inquiry, 'service') ?>
		<?php echo $form->textfield($inquiry, 'service_name') ?>
		<?php echo $form->hiddenField($shipment, 'service_id',array('class' => 'field_calc_charge')) ?>
		<?php echo $form->hiddenField($inquiry, 'domestic_ratePrice_id',array('class' => 'field_calc_charge')) ?>
		<?php echo $form->error($shipment, 'service_id') ?>
	</div>

	<h2>Items Detail</h2>
	<div class="item-head">
		<div class="i-title">Name</div>
		<div class="i-value">Value</div>
		<div class="i-weight">Weight (kg)</div>
		<div class="i-length">Length (cm)</div>
		<div class="i-width">Width (cm)</div>
		<div class="i-height">Height (cm)</div>
		<div class="i-volmat">Volume Metric (kg)</div>
		<div class="clearfix"></div>
	</div>
	<div id="items">
		<?php for ($i = 0; $i < count($items); $i++): ?>
			<?php
			$this->renderPartial('_item', array(
				'item' => $items[$i],
				'index' => $i,
			))
			?>
		<?php endfor; ?>
	</div>
	<div class="clearfix"></div>
	<?php echo CHtml::button('Add Item', array('id' => 'add-item')) ?>

</fieldset>

<fieldset>
	<legend>Estimated Transit Time</legend>
	
	<div class="subcolumns">
		<div class="c48l">
			
			<div class="row">
				<?php echo $form->labelEx($inquiry, 'pickup_date') ?>
				<?php
				$this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'attribute' => 'pickup_date',
					'model' => $inquiry,
					'options' => array(
						'yearRange' => '-0:+7',
						'changeYear' => 'true',
						'changeMonth' => 'true',
					),
				));
				?>
				<?php echo $form->error($inquiry, 'pickup_date') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($inquiry, 'delivery_date') ?>
				<?php
				$this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'attribute' => 'delivery_date',
					'model' => $inquiry,
					'options' => array(
						'yearRange' => '-0:+7',
						'changeYear' => 'true',
						'changeMonth' => 'true',
					),
				));
				?>
				<?php echo $form->error($inquiry, 'delivery_date') ?>
			</div>
		</div>
		<div class="c48r">
			<div class="row">
				<?php echo $form->labelEx($inquiry, 'courier') ?>
				<?php echo CHtml::textField('courier-disabled', '', array('id' => 'c-dib','disabled' => 'disabled')) ?>
				<?php echo $form->hiddenField($inquiry, 'courier') ?>
				<?php echo $form->error($inquiry, 'courier') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($inquiry, 'transit_time') ?>
				<?php echo CHtml::textField('transit_time-disabled','',array('size' => 3,'disabled'=>'disabled','id' => 't-dib')) ?> days
				<?php echo $form->hiddenField($inquiry, 'transit_time') ?>
				<?php echo $form->error($inquiry, 'transit_time') ?>
			</div>
		</div>
	</div>
	
</fieldset>
<div>
	<?php 
		echo CHtml::ajaxButton('Calculate Charges', array('shipment/getShippingCosts'), array(
			'type' => 'post',
			'dataType' => 'json',
			'data' => 'js:$("input.field_calc_charge,input[name*=ShipmentItem]").serialize()',
			'success' => 'js:function(data){
					$("#InquiryForm_freight_charges").val(data.freight_charges);
					$("#InquiryForm_fuel_charges").val(data.fuel_charges);
					$("#InquiryForm_vat").val(data.vat);
					$("#InquiryForm_shipping_charges").val(data.shipping_charges);
					$("#Shipment_charges").val(data.shipping_charges);
					window.location = "#ship-rate";
				}'
		)); 
	?>
	<?php // echo CHtml::button('Back', array('id' => 'back')) ?>
</div>
<?php echo $form->hiddenField($shipment,'service_type',array('class' => 'field_calc_charge')) ?>
<?php Yii::app()->clientScript->registerCoreScript("jquery") ?>
<?php 
	$get_ttime = CHtml::ajax(array(
				'url' => array('shipment/getEstimatedDeliveryDate'),
				'dataType' => 'json',
				'type' => 'post',
				'data' => 'js:$("*[name*=InquiryForm]").serialize()',
				'success' => 'js:function(data){
					$("#InquiryForm_delivery_date").val(data);
				}'
	));

$getRateUrl = Yii::app()->createUrl('shipment/getRates');
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/fancybox/jquery.fancybox-1.3.1.pack.js');
$cs->registerCssFile(Yii::app()->baseUrl.'/js/fancybox/jquery.fancybox-1.3.1.css');
$getRates = <<<EOD
$("#InquiryForm_service_name").live('click',function(){
	var that = $(this);
	that.attr("disabled","disabled");
	var zone_country = $("#InquiryForm_receiver_country_code").val();
	var zone_id = $("#InquiryForm_receiver_zone_code").val();
	var district_id =  $("#InquiryForm_receiver_city_code").val();
	var postcode = $("#InquiryForm_receiver_postal").val();
	var country_id = $("#InquiryForm_receiver_country_id").val();
	$.fancybox({
		'href' : '$getRateUrl',
		ajax   : {
				type : "POST",
				data : {zone_id : zone_id,district_id : district_id, postcode : postcode,zone_country:zone_country,country_id:country_id}
		},
		onComplete : function(){
			$("#all-rates a.use-this").click(function(){
				$("#InquiryForm_data_rate").val($(this).attr("id"));
				$("#all-rates tr").removeClass("used");
				$(this).parent().parent().addClass("used");
				$.fancybox.close();
				return false;
			});
		},
		onClosed	:	function() {
			var r_data = $("#InquiryForm_data_rate").val().split("~");
			if(r_data.length > 1){
				$("#InquiryForm_service_name").val(r_data[2]);
				$("#Shipment_service_id").val(r_data[1]);
				$("#Shipment_service_type").val(r_data[0]);
				$("#InquiryForm_courier,#c-dib").val(r_data[3]);
				$("#InquiryForm_transit_time,#t-dib").val(r_data[4]);
				if(r_data[0] == 'international'){
					var idx = r_data[5] == 'document' ? 0:1;
					$("input:radio[name=Shipment[type]]")[idx].checked = true;
				}else if(r_data[0] == 'domestic'){
					$("#InquiryForm_domestic_ratePrice_id").val(r_data[5]);
				}
				that.removeAttr("disabled");
				$get_ttime
			}
		}
	});
});
EOD;
$cs->registerScript('change_service_list2', $getRates);
$back = <<<EOD
$("#back").click(function(){
	$(".form #ship-detail").fadeOut("slow",function(){
				$(".form #from-to").fadeIn();
	});
});
EOD;
$cs->registerScript('back-to-fromto', $back);
?>
<script type="text/javascript">
	$("#add-item").click(function(){
		$.ajax({
			success: function(html){
				$("#items").append(html);
			},
			type: 'get',
			url: '<?php echo $this->createUrl('item') ?>',
			data: {
				index: $("#items .item-container").size()
			},
			cache: false,
			dataType: 'html'
		});
	});
	
	$('#InquiryForm_pickup_date').live('change',function(){
		<?php echo $get_ttime; ?>
	});
</script>