<p class="note">Fields with <span class="required">*</span> are required.</p>
<fieldset>
	<div class="subcolumns" id="inquiry">
		<div class="c48l">
			<fieldset>
				<legend>Origin</legend>
				<?php 
				/**
					 * creating ajax function untuk mencari daftar city berdasarkan provinceId
					 * return array dari city
					 *  
					 */
					$sugggestCity = CHtml::ajax(array(
								'url' => array('shipment/suggestCity'),
								'dataType' => 'json',
								'data' => array(
									'term' => 'js:request.term',
								),
								'success' => 'js:function(data){
														realData=$.makeArray(data);
															response($.map(realData, function (item){return{
																	did:item.did,
																	zid:item.zid,
																	value:item.value,
																	label:item.label
																}
															}))
													}'
									)
					);
				?>
				
				<div class="row">
					<?php echo $form->labelEx($inquiry, 'shipper_country') ?>
					<?php
					$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
						'model' => $inquiry,
						'attribute' => 'shipper_country',
						'sourceUrl' => array('shipment/suggestInternationalZoneCountry'),
						'options' => array(
							'select' => 'js:function(event,ui){
									$("#InquiryForm_shipper_country_code").val(ui.item.id);
									return true;
								}',
						)
							)
					);

					?>
					<?php echo $form->error($inquiry, 'shipper_country'); ?>
					<?php echo $form->hiddenField($inquiry, 'shipper_country_code') ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($inquiry, 'shipper_city') ?>
					<?php
					/**
					 * render the auto complete 
					 */
					$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
						'model' => $inquiry,
						'attribute' => 'shipper_city',
						'source' => "js:function(request,response){{$sugggestCity}}",
						'options' => array(
							'select' => 'js:function(event,ui){
									$("#InquiryForm_shipper_city_code").val(ui.item.did);
									return true;
								}',
							)
						)
					);

					?>
				<?php echo $form->error($inquiry, 'shipper_city'); ?>
				<?php echo $form->hiddenField($inquiry, 'shipper_city_code') ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($inquiry, 'shipper_postal') ?>
					<?php echo $form->textField($inquiry, 'shipper_postal',array('value' => $customer->getContactData()->postal)) ?>
					<?php echo $form->error($inquiry, 'shipper_postal') ?>
				</div>

			</fieldset>
		</div>

		<div class="c48r">
			<fieldset>
				<legend>Destination</legend>

				<div class="row">
					<?php echo $form->labelEx($inquiry, 'receiver_country') ?>
					<?php
					$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
						'model' => $inquiry,
						'attribute' => 'receiver_country',
						'sourceUrl' => array('shipment/suggestInternationalZoneCountry'),
						'options' => array(
							'select' => 'js:function(event,ui){
									$("#InquiryForm_receiver_country_code").val(ui.item.zone);
									$("#InquiryForm_receiver_country_id").val(ui.item.id);
									return true;
								}',
						)
							)
					);

					?>
					<?php echo $form->error($inquiry, 'receiver_country'); ?>
				</div>
				<?php echo $form->hiddenField($inquiry, 'receiver_country_code',array('class' => 'field_calc_charge')) ?>
				<?php echo $form->hiddenField($inquiry, 'receiver_country_id') ?>

				<div class="row">
					<?php echo $form->labelEx($inquiry, 'receiver_city') ?>
					<?php
					/**
					 * render the auto complete 
					 */
					$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
						'model' => $inquiry,
						'attribute' => 'receiver_city',
						'source' => "js:function(request,response){{$sugggestCity}}",
						'options' => array(
							'select' => 'js:function(event,ui){
									$("#InquiryForm_receiver_city_code").val(ui.item.did);
									$("#InquiryForm_receiver_zone_code").val(ui.item.zid);
									return true;
								}',
						)
							)
					);

					?>
					<?php echo $form->error($inquiry, 'receiver_city'); ?>
				</div>
				<?php echo $form->hiddenField($inquiry, 'receiver_city_code') ?>
				<?php echo $form->hiddenField($inquiry, 'receiver_zone_code') ?>

				<div class="row">
					<?php echo $form->labelEx($inquiry, 'receiver_postal') ?>
					<?php echo $form->textField($inquiry, 'receiver_postal') ?>
					<?php echo $form->error($inquiry, 'receiver_postal') ?>
				</div>

			</fieldset>
		</div>
	</div>
	<div class="clearfix"></div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Rate', array('id' => 'rate-btn','name' => 'rate-btn')); ?>
	</div>
</fieldset>

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
$cs->registerCoreScript('jquery');
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/fancybox/jquery.fancybox-1.3.1.pack.js');
$cs->registerCssFile(Yii::app()->baseUrl.'/js/fancybox/jquery.fancybox-1.3.1.css');
$getRates = <<<EOD
$("#shipment-form input[type=submit]").click(function() {
    $("input[type=submit]").removeAttr("clicked");
    $(this).attr("clicked", "true");
});
function valideteFancy(form, data, hasError){
	var btn = $("input[type=submit][clicked=true]").val();
	if(btn == 'Rate'){
		var zone_country = $("#InquiryForm_receiver_country_code").val();
		var zone_id = $("#InquiryForm_receiver_zone_code").val();
		var district_id =  $("#InquiryForm_receiver_city_code").val();
		var postcode = $("#InquiryForm_receiver_postal").val();
		var country_id = $("#InquiryForm_receiver_country_id").val();
		if(!hasError){
			$.fancybox({
				modal  : true,
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
//						return false;
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
						}
						else if(r_data[0] == 'domestic'){
							$("#InquiryForm_domestic_ratePrice_id").val(r_data[5]);
						}
//						$(".form #from-to").fadeOut("slow",function(){
//							$(".form #ship-detail").fadeIn();
//						});
						$get_ttime
					}
				}
			});
		}
	}
	else return true;
}
EOD;
$cs->registerScript('change_service_list', $getRates);
?>