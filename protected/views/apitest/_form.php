<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'shipment-form',
	'enableAjaxValidation'=>false,
	'action' => array('/service/requestpickup'),
)); ?>
	<fieldset>
		<legend>Shipping Data</legend>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php 
	if($form instanceof CActiveForm);
	?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'service_id'); ?>
		<?php echo $form->textField($model,'service_id',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'service_id'); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('Auth Code', 'key') ?>
		<?php echo CHtml::textField('key', 'febrifebri') ?>
	</div>
	
	<div class="subcolumns">
		<div class="c50l">
			<div class="subcl">
				<div class="row">
					<?php echo $form->labelEx($model,'shipper_name'); ?>
					<?php echo $form->textField($model,'shipper_name',array('size'=>35,'maxlength'=>255)); ?>
					<?php echo $form->error($model,'shipper_name'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model,'shipper_address'); ?>
					<?php echo $form->textArea($model,'shipper_address',array('rows'=>6, 'cols'=>50)); ?>
					<?php echo $form->error($model,'shipper_address'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model,'shipper_province'); ?>
					<?php echo $form->textField($model,'shipper_province',array('size'=>35,'maxlength'=>80)); ?>
					<?php echo $form->error($model,'shipper_province'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model, 'shipper_city'); ?>
					<?php echo $form->textField($model,'shipper_city',array('size'=>35,'maxlength'=>80)); ?>
					<?php echo $form->error($model, 'shipper_city'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model,'shipper_postal'); ?>
					<?php echo $form->textField($model,'shipper_postal',array('size'=>7,'maxlength'=>20)); ?>
					<?php echo $form->error($model,'shipper_postal'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model,'shipper_country'); ?>
					<?php echo $form->textField($model,'shipper_country',array('size'=>35,'maxlength'=>45)); ?>
					<?php echo $form->error($model,'shipper_country'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model,'shipper_phone'); ?>
					<?php echo $form->textField($model,'shipper_phone',array('size'=>35,'maxlength'=>30)); ?>
					<?php echo $form->error($model,'shipper_phone'); ?>
				</div>
			</div>
		</div>
		
		<div class="c50r">
			<div class="subcr">
				<div class="row">
					<?php echo $form->labelEx($model,'receiver_name'); ?>
					<?php echo $form->textField($model,'receiver_name',array('size'=>35,'maxlength'=>255)); ?>
					<?php echo $form->error($model,'receiver_name'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model,'receiver_address'); ?>
					<?php echo $form->textArea($model,'receiver_address',array('rows'=>6, 'cols'=>50)); ?>
					<?php echo $form->error($model,'receiver_address'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model, 'receiver_city'); ?>
					<?php echo $form->textField($model,'receiver_city'); ?>
					<?php echo $form->error($model, 'receiver_city'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model,'receiver_province'); ?>
					<?php echo $form->textField($model,'receiver_province',array('size'=>35,'maxlength'=>80)); ?>
					<?php echo $form->error($model,'receiver_province'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model,'receiver_attn'); ?>
					<?php echo $form->textField($model,'receiver_attn',array('size'=>35,'maxlength'=>255)); ?>
					<?php echo $form->error($model,'receiver_attn'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model,'receiver_postal'); ?>
					<?php echo $form->textField($model,'receiver_postal',array('size'=>35,'maxlength'=>30)); ?>
					<?php echo $form->error($model,'receiver_postal'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model,'receiver_country'); ?>
					<?php echo $form->textField($model,'receiver_country',array('size'=>35,'maxlength'=>45)); ?>
					<?php echo $form->error($model,'receiver_country'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model,'receiver_phone'); ?>
					<?php echo $form->textField($model,'receiver_phone',array('size'=>35,'maxlength'=>30)); ?>
					<?php echo $form->error($model,'receiver_phone'); ?>
				</div>
			</div>
		</div>
	</div>
        <div class="subcolumns">
            <div class="c501">
                <div class="subc1">
                    <div class="row">
                        <?php echo $form->labelEx($model,'service_type'); ?>
                        <div id="status-radiobutton">
                        <?php echo $form->radioButtonList($model,'service_type',$model->Service_type); ?>
                        </div>
                        <?php echo $form->error($model,'service_type'); ?>
                    </div>
                </div>
            </div>
        </div>

	<div class="subcolumns">
		<div class="c50l">
			<div class="subcl">
				<div class="row">
					<?php echo $form->labelEx($model,'shipment_description'); ?>
					<?php echo $form->textArea($model,'shipment_description',array('rows'=>6, 'cols'=>50)); ?>
					<?php echo $form->error($model,'shipment_description'); ?>
				</div>
			</div>
		</div>

		<div class="c50r">
			<div class="subcr">
				<div class="row">
					<?php echo $form->labelEx($model,'delivery_instruction'); ?>
					<?php echo $form->textArea($model,'delivery_instruction',array('rows'=>6, 'cols'=>50)); ?>
					<?php echo $form->error($model,'delivery_instruction'); ?>
				</div>
				
				<div class="row">
					<?php echo $form->labelEx($model,'goods_type'); ?>
					<?php echo $form->dropDownList($model,'goods_type', array_merge(array(''=>'--'),$goods_type),array()); ?>
					<?php echo $form->error($model,'goods_type'); ?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
    <?php echo $form->labelEx($model, 'type'); ?>
		<div id="status-radiobutton">
    <?php echo $form->radioButtonList($model, 'type', $model->listtype); ?>
		</div>
    <?php echo $form->error($model, 'type'); ?>
  </div>
	<div id="items">
		<table class="item">
				<tbody>
					<tr>
					<td class="nm_item">Nama</td>
					<td class="desc_item">Nilai</td>
					<td class="brt_item">Berat (kg)</td>
					<td>Panjang (cm)</td>
					<td>Lebar (cm)</td>
					<td>Tinggi (cm)</td>
					<td>Volume Metric (kg)</td>
				</tr>
				<tr>
					<td><input type="text" maxlength="255" id="ShipmentItem_0_title" name="ShipmentItem[0][title]" value="TesBarangAPI"></td>
					<td><input type="text" maxlength="12" id="ShipmentItem_0_amount" name="ShipmentItem[0][amount]" size="10"></td>
					<td><input type="text" id="ShipmentItem_0_package_weight" name="ShipmentItem[0][package_weight]" size="5" value="20"></td>
					<td><input type="text" id="ShipmentItem_0_package_length" name="ShipmentItem[0][package_length]" size="3"></td>
					<td><input type="text" id="ShipmentItem_0_package_width" name="ShipmentItem[0][package_width]" size="3"></td>
					<td><input type="text" id="ShipmentItem_0_package_height" name="ShipmentItem[0][package_height]" size="3"></td>
					<td><input type="text" id="ShipmentItem_0_package_weight_vol" name="ShipmentItem[0][package_weight_vol]" size="6" disabled="disabled"></td>
					<input type="hidden" id="ShipmentItem_0_id" name="ShipmentItem[0][id]">		</tr>
			</tbody>
		</table>
		
		<table class="item">
				<tbody>
					<tr>
					<td class="nm_item">Nama</td>
					<td class="desc_item">Nilai</td>
					<td class="brt_item">Berat (kg)</td>
					<td>Panjang (cm)</td>
					<td>Lebar (cm)</td>
					<td>Tinggi (cm)</td>
					<td>Volume Metric (kg)</td>
				</tr>
				<tr>
					<td><input type="text" maxlength="255" id="ShipmentItem_1_title" name="ShipmentItem[1][title]" value="BarangAPI2"></td>
					<td><input type="text" maxlength="12" id="ShipmentItem_1_amount" name="ShipmentItem[1][amount]" size="10"></td>
					<td><input type="text" id="ShipmentItem_1_package_weight" name="ShipmentItem[1][package_weight]" size="5" value="15"></td>
					<td><input type="text" id="ShipmentItem_1_package_length" name="ShipmentItem[1][package_length]" size="3"></td>
					<td><input type="text" id="ShipmentItem_1_package_width" name="ShipmentItem[1][package_width]" size="3"></td>
					<td><input type="text" id="ShipmentItem_1_package_height" name="ShipmentItem[1][package_height]" size="3"></td>
					<td><input type="text" id="ShipmentItem_1_package_weight_vol" name="ShipmentItem[1][package_weight_vol]" size="6" disabled="disabled"></td>
					<input type="hidden" id="ShipmentItem_1_id" name="ShipmentItem[1][id]">		</tr>
			</tbody>
		</table>
		
		<table class="item">
				<tbody>
					<tr>
					<td class="nm_item">Nama</td>
					<td class="desc_item">Nilai</td>
					<td class="brt_item">Berat (kg)</td>
					<td>Panjang (cm)</td>
					<td>Lebar (cm)</td>
					<td>Tinggi (cm)</td>
					<td>Volume Metric (kg)</td>
				</tr>
				<tr>
					<td><input type="text" maxlength="255" id="ShipmentItem_2_title" name="ShipmentItem[2][title]" value="TesBarangAPI3"></td>
					<td><input type="text" maxlength="12" id="ShipmentItem_2_amount" name="ShipmentItem[2][amount]" size="10"></td>
					<td><input type="text" id="ShipmentItem_2_package_weight" name="ShipmentItem[2][package_weight]" size="5" value="20"></td>
					<td><input type="text" id="ShipmentItem_2_package_length" name="ShipmentItem[2][package_length]" size="3"></td>
					<td><input type="text" id="ShipmentItem_2_package_width" name="ShipmentItem[2][package_width]" size="3"></td>
					<td><input type="text" id="ShipmentItem_2_package_height" name="ShipmentItem[2][package_height]" size="3"></td>
					<td><input type="text" id="ShipmentItem_2_package_weight_vol" name="ShipmentItem[2][package_weight_vol]" size="6" disabled="disabled"></td>
					<input type="hidden" id="ShipmentItem_2_id" name="ShipmentItem[2][id]">		</tr>
			</tbody>
		</table>
	</div>
	</fieldset>
	
	<fieldset>
		<legend>Oder IntraCity Required Data</legend>
		<div class="row">
			<?php echo $form->label($model_city,'area_id') ?>
			<?php echo $form->textField($model_city, 'area_id', array('size'=>3)) ?>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Order Domestic Required Data</legend>
		<div class="row">
			<?php echo $form->label($model_domestic, 'zone_id')?>
			<?php echo $form->textField($model_domestic, 'zone_id',array('size'=>3))?>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Order International Required Data</legend>
		<div class="row">
			<?php echo $form->label($model_international, 'zone')?>
			<?php echo $form->textField($model_international, 'zone',array('size'=>5))?>
		</div>
	</fieldset>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->
