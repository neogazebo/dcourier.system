<div class="form">

	<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'shipment-form',
		'enableAjaxValidation' => false,
			));

	?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php
	if ($form instanceof CActiveForm)
		;

	echo $form->errorSummary($model);
	$randawb = rand(1000000000, 9999999999);

	?>
	<div class="row">
		<?php echo $form->labelEx($model, 'awb'); ?>
		<?php echo $form->textField($model, 'awb', array('size' => 35, 'maxlength' => 255, 'disabled' => 'disabled', 'value' => $model->isNewRecord ? $randawb : $model->awb)); ?>
		<?php echo $form->hiddenField($model, 'awb', array('value' => $model->isNewRecord ? $randawb : $model->awb)); ?>
		<?php echo $form->error($model, 'awb'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'customer_name'); ?>
		<?php
		$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
			'model' => $model,
			'sourceUrl' => array('/shipment/SuggestCustomer', 'to_search' => 'name'),
			'options' => array(
				'select' => 'js:function(event,ui){
							$.getJSON("' . $this->createUrl('shipment/GetCustomerDetail') . '&customer_id="+ui.item.id,function(data){
								$("#Shipment_shipper_name").val(data.full_name);
								$("#Shipment_shipper_address").val(data.address);
								$("#Shipment_shipper_city").val(data.city);
								$("#Shipment_shipper_postal").val(data.post);
								$("#Shipment_shipper_phone").val(data.phone1);
								$("#Shipment_customer_id").val(ui.item.id);
							});
              return true;
           }'
			),
			'htmlOptions' => array(
				'name' => 'customer_name',
				'size' => 35,
			)
		));

		?>
	</div>
	OR
	<div class="row">
		<?php echo $form->labelEx($model, 'customer_id'); ?>
		<?php
		$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
			'model' => $model,
			'attribute' => 'customer_id',
			'sourceUrl' => array('/shipment/SuggestCustomer', 'to_search' => 'id'),
			'options' => array(
				'select' => 'js:function(event,ui){
							$.getJSON("' . $this->createUrl('shipment/GetCustomerDetail') . '&customer_id="+ui.item.value,function(data){
								$("#Shipment_shipper_name").val(data.full_name);
								$("#Shipment_shipper_address").val(data.address);
								$("#Shipment_shipper_city").val(data.city);
								$("#Shipment_shipper_postal").val(data.post);
								$("#Shipment_shipper_phone").val(data.phone1);
								$("#customer_name").val(ui.item.id);
							});
              return true;
           }'
			),
			'htmlOptions' => array(
				'size' => 30,
			)
		));

		?>
	</div>

	<div class="subcolumns">
		<div class="c50l">
			<div class="subcl">
				<div class="row">
					<?php echo $form->labelEx($model, 'shipper_name'); ?>
					<?php echo $form->textField($model, 'shipper_name', array('size' => 35, 'maxlength' => 255)); ?>
					<?php echo $form->error($model, 'shipper_name'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model, 'shipper_address'); ?>
					<?php echo $form->textArea($model, 'shipper_address', array('rows' => 6, 'cols' => 50)); ?>
					<?php echo $form->error($model, 'shipper_address'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model, 'shipper_province'); ?>
					<?php
					$this->widget('ext.CSuggest', array(
						'model' => $model,
						'attribute' => 'shipper_province',
						'sourceUrl' => array('suggest', 'from' => 'province', 'select' => '{name}{id}'),
						'form' => $form,
						'returnId' => false,
						'options' => array(
							'change' => 'js:function(event,ui){
											if(ui.item){

											}
											else{
											}
										 }'
						),
						'value' => $model->shipper_province,
					));

					?>

					<?php echo $form->error($model, 'shipper_province'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model, 'shipper_city'); ?>
					<?php
					$cajax = CHtml::ajax(array(
								'url' => CHtml::normalizeUrl(array('suggest', 'from' => 'district', 'select' => '{name}{id}')),
								'dataType' => 'json',
								'data' => array(
									'term' => 'js:request.term',
									'where[province_id][province][name]' => 'js:$("input#Shipment_shipper_province").val()',
								),
								'success' => 'js:function(data){
															realData=$.makeArray(data);
															response($.map(realData, function (item){return{
																	id:item.id,
																	value:item.value,
																}
															}))
														}'
							));
					$this->widget('ext.CSuggest', array(
						'model' => $model,
						'attribute' => 'shipper_city',
						'source' => "js:function(request,response){{$cajax}}",
						'form' => $form,
						'returnId' => false,
						'value' => $model->shipper_city,
					));

					?>
					<?php echo $form->error($model, 'shipper_city'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model, 'shipper_postal'); ?>
					<?php
					$this->widget('ext.CSuggest', array(
						'model' => $model,
						'attribute' => 'shipper_postal',
						'sourceUrl' => array('suggest', 'from' => 'area', 'select' => '{postcode}{id}'),
						'form' => $form,
						'returnId' => false,
						'value' => $model->shipper_postal,
					));

					?>

					<?php echo $form->error($model, 'shipper_postal'); ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model, 'shipper_country'); ?>
					<?php echo $form->textField($model, 'shipper_country', array('size' => 35, 'maxlength' => 45)); ?>
					<?php echo $form->error($model, 'shipper_country'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model, 'shipper_phone'); ?>
					<?php echo $form->textField($model, 'shipper_phone', array('size' => 35, 'maxlength' => 30)); ?>
					<?php echo $form->error($model, 'shipper_phone'); ?>
				</div>
			</div>
		</div>

		<div class="c50r">
			<div class="subcr">
				<div class="row">
					<?php echo $form->labelEx($model, 'receiver_name'); ?>
					<?php echo $form->textField($model, 'receiver_name', array('size' => 35, 'maxlength' => 255)); ?>
					<?php echo $form->error($model, 'receiver_name'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model, 'receiver_address'); ?>
					<?php echo $form->textArea($model, 'receiver_address', array('rows' => 6, 'cols' => 50)); ?>
					<?php echo $form->error($model, 'receiver_address'); ?>
				</div>

				<div class="row at_comp">
					<?php echo $form->labelEx($model, 'receiver_province') ?>
					<?php
					$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
						'model' => $model,
						'attribute' => 'receiver_province',
						'sourceUrl' => array('shipment/suggestProvince'),
						'options' => array(
							'select' => 'js:function(event,ui){
									$("#Shipment_receiver_province").val(ui.item.value);
									$("#ShipmentDomestic_province_id").val(ui.item.id);
									return true;
								}',
						)
							)
					);

					?>
				</div>

				<?php echo $form->error($model, 'receiver_province'); ?>
				<?php echo $form->hiddenField($model_domestic, 'province_id', array('class' => 'model_domestic')); ?>

				<div class="row at_comp">
					<?php echo $form->labelEx($model, 'receiver_city') ?>
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
									'pid' => 'js:$("input#ShipmentDomestic_province_id").val()'
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

					/**
					 * render the auto complete 
					 */
					$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
						'model' => $model,
						'attribute' => 'receiver_city',
						'source' => "js:function(request,response){{$sugggestCity}}",
						'options' => array(
							'select' => 'js:function(event,ui){
									$("#ShipmentDomestic_zone_id").val(ui.item.zid);
									$("#ShipmentDomestic_district_id").val(ui.item.did);
									return true;
								}',
						)
							)
					);

					?>
				</div>

				<?php echo $form->error($model, 'receiver_city'); ?>
				<?php echo $form->hiddenField($model_domestic, 'district_id', array('class' => 'model_domestic')); ?>
				<?php echo $form->hiddenField($model_domestic, 'zone_id', array('class' => 'model_domestic')); ?>

				<div class="row">
					<?php echo $form->labelEx($model, 'receiver_attn'); ?>
					<?php echo $form->textField($model, 'receiver_attn', array('size' => 35, 'maxlength' => 255)); ?>
					<?php echo $form->error($model, 'receiver_attn'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model, 'receiver_postal'); ?>
					<?php echo $form->textField($model, 'receiver_postal', array('size' => 35, 'maxlength' => 30)); ?>
					<?php echo $form->error($model, 'receiver_postal'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model, 'receiver_country'); ?>
					<?php echo $form->textField($model, 'receiver_country', array('size' => 35, 'maxlength' => 45)); ?>
					<?php echo $form->error($model, 'receiver_country'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model, 'receiver_phone'); ?>
					<?php echo $form->textField($model, 'receiver_phone', array('size' => 35, 'maxlength' => 30)); ?>
					<?php echo $form->error($model, 'receiver_phone'); ?>
				</div>
			</div>
		</div>
	</div>

	<div class="subcolumns">
		<div class="c50l">
			<div class="subcl">
				<div class="row">
					<?php echo $form->labelEx($model, 'shipment_description'); ?>
					<?php echo $form->textArea($model, 'shipment_description', array('rows' => 6, 'cols' => 50)); ?>
					<?php echo $form->error($model, 'shipment_description'); ?>
				</div>
			</div>
		</div>

		<div class="c50r">
			<div class="subcr">
				<div class="row">
					<?php echo $form->labelEx($model, 'delivery_instruction'); ?>
					<?php echo $form->textArea($model, 'delivery_instruction', array('rows' => 6, 'cols' => 50)); ?>
					<?php echo $form->error($model, 'delivery_instruction'); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="subcolumns">
		<div class="c50l">
			<div class="row">
				<?php echo $form->labelEx($model, 'type'); ?>
				<?php echo $form->hiddenField($model, 'type'); ?>
				<div id="status-radiobutton">
					<?php echo $form->radioButtonList($model, 'type', $model->listtype); ?>
				</div>
				<?php echo $form->error($model, 'type'); ?>
			</div>

		</div>
		<div class="c501r">

			<div class="row">
				<?php echo $form->labelEx($model, 'goods_type'); ?>
				<?php echo $form->dropDownList($model, 'goods_type', array_merge(array('' => '--'), $goods_type), array()); ?>
				<?php echo $form->error($model, 'goods_type'); ?>
			</div>

			<div class="row">
				<div id="status-radiobutton">
					<?php echo $form->checkBox($model, 'insurance'); ?>
					<?php echo $form->label($model, 'insurance'); ?>
					<?php echo $form->error($model, 'insurance'); ?>
				</div>
			</div>
			<div class="row">
				<?php echo $form->label($model, 'shipping_status'); ?>
				<div id="status-radiobutton">
					<?php echo $form->radioButtonList($model, 'shipping_status', array('waitingforpickup' => 'Waiting for pick up', 'progress' => 'Progress', 'delivered' => 'Delivered')); ?>
					<?php echo $form->error($model, 'shipping_status'); ?>
				</div>
			</div>

			<h2>Data Barang</h2>
			<br />
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
			<?php echo CHtml::button('Add Item', array('id' => 'add-item')) ?>
			<br />

			<h2>Biaya Tambahan</h2>
			<br />
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
			<?php echo CHtml::button('Add Cost', array('id' => 'add-cost')) ?>
			<div class="row buttons">

				<?php Yii::app()->clientScript->registerCoreScript("jquery") ?>
				<script>
					$("#add-item").click(function(){
						$.ajax({
							success: function(html){
								$("#items").append(html);
							},
							type: 'get',
							url: '<?php echo $this->createUrl('item') ?>',
							data: {
								index: $("#items .item").size()
							},
							cache: false,
							dataType: 'html'
						});
					});
				
					$("#add-cost").click(function(){
						$.ajax({
							success: function(html){
								$("#costs").append(html);
							},
							type: 'get',
							url: '<?php echo $this->createUrl('cost') ?>',
							data: {
								index: $("#costs .cost").size()
							},
							cache: false,
							dataType: 'html'
						});
					});
				</script>
			</div>

		</div>

		<div class="row buttons">
			<?php
			echo CHtml::ajaxButton('View Available Services', array('shipment/domesticService'), array(
				'type' => 'post',
				'dataType' => 'html',
				'data' => 'js:$("input.model_domestic").serialize()+"&"+$("input[name*=ShipmentItem]").serialize()',
				'success' => 'js:function(data){
							$(".services-list").children().remove();
							$(".services-list").append(data);
							return true;
						}'
					)
			)

			?>
		</div>

		<div class="row services-list"></div>

	</div>
</div>

<?php // if ($model->getScenario() == 'update'):; ?>

<!--	<div class="row">-->
		<?php // echo $form->labelEx($model, 'charges'); ?>
		 <?php // echo $form->textField($model, 'charges', array('disabled' => 'disabled', 'size' => '20')); ?>
<!--	</div>-->
<?php // endif; ?>

<div class="row buttons">
	<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->