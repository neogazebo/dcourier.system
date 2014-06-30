<div class="form wide">
	<fieldset>
		<?php echo CHtml::link('pdf waybill',Yii::app()->createUrl('shipment/PDFAwb',array('id'=>$shipment->id)),array('target'=>'blank')); ?>
		<div class="subcolumns">
			<div class="c50l">
<!--				<div class="row">
					<?php // echo CHtml::activeLabelEx($shipment, 'pickup_date') ?>
					<?php // echo CHtml::activeTextField($shipment, 'pickup_date',array('disabled' => 'disabled')) ?>
				</div>-->
				<div class="row">
					<?php echo CHtml::activeLabelEx($customer, 'accountnr') ?>
					<?php echo CHtml::activeTextField($customer, 'accountnr',array('disabled' => 'disabled')) ?>
				</div>
			</div>
			<div class="c50r">
				<div class="row">
					<?php echo CHtml::activeLabelEx($shipment, 'awb') ?>
					<?php echo CHtml::activeTextField($shipment, 'awb',array('disabled' => 'disabled')) ?>
				</div>
				<div class="row">
					<?php echo CHtml::activeLabelEx($shipment, 'carrier_awb') ?>
					<?php echo CHtml::activeTextField($shipment, 'carrier_awb',array('disabled' => 'disabled')) ?>
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<div class="subcolumns">
			<div class="c50l">
				<div class="row">
					<?php echo CHtml::activeLabelEx($shipment, 'shipper_name') ?>
					<?php echo CHtml::activeTextField($shipment, 'shipper_name',array('disabled' => 'disabled')) ?>
				</div>
				<div class="row">
					<?php echo CHtml::activeLabelEx($shipment, 'shipper_address') ?>
					<?php echo CHtml::activeTextField($shipment, 'shipper_address',array('disabled' => 'disabled')) ?>
				</div>
				<div class="row">
					<?php echo CHtml::activeLabelEx($shipment, 'shipper_city') ?>
					<?php echo CHtml::activeTextField($shipment, 'shipper_city',array('disabled' => 'disabled')) ?>
				</div>
				<div class="row">
					<?php echo CHtml::activeLabelEx($shipment, 'shipper_postal') ?>
					<?php echo CHtml::activeTextField($shipment, 'shipper_postal',array('disabled' => 'disabled')) ?>
				</div>
				<div class="row">
					<?php echo CHtml::activeLabelEx($shipment, 'shipper_phone') ?>
					<?php echo CHtml::activeTextField($shipment, 'shipper_phone',array('disabled' => 'disabled')) ?>
				</div>
			</div>
			<div class="c50r">
				<div class="row">
					<?php echo CHtml::activeLabelEx($shipment, 'receiver_name') ?>
					<?php echo CHtml::activeTextField($shipment, 'receiver_name',array('disabled' => 'disabled')) ?>
				</div>
				<div class="row">
					<?php echo CHtml::activeLabelEx($shipment, 'receiver_address') ?>
					<?php echo CHtml::activeTextField($shipment, 'receiver_address',array('disabled' => 'disabled')) ?>
				</div>
				<div class="row">
					<?php echo CHtml::activeLabelEx($shipment, 'receiver_city') ?>
					<?php echo CHtml::activeTextField($shipment, 'receiver_city',array('disabled' => 'disabled')) ?>
				</div>
				<div class="row">
					<?php echo CHtml::activeLabelEx($shipment, 'receiver_postal') ?>
					<?php echo CHtml::activeTextField($shipment, 'receiver_postal',array('disabled' => 'disabled')) ?>
				</div>
				<div class="row">
					<?php echo CHtml::activeLabelEx($shipment, 'receiver_phone') ?>
					<?php echo CHtml::activeTextField($shipment, 'receiver_phone',array('disabled' => 'disabled')) ?>
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<div class="subcolumns">
			<div class="c50l">
				<div class="row">
					<?php echo CHtml::activeLabelEx($shipment, 'type') ?>
					<?php echo CHtml::activeTextField($shipment, 'type',array('disabled' => 'disabled')) ?>
				</div>
				<div class="row">
					<?php echo CHtml::activeLabelEx($shipment, 'pieces') ?>
					<?php echo CHtml::activeTextField($shipment, 'pieces',array('disabled' => 'disabled')) ?>
				</div>
<!--				<div class="row">
					<?php // echo CHtml::activeLabelEx($shipment, 'pickup_note') ?>
					<?php // echo CHtml::activeTextField($shipment, 'pickup_note',array('disabled' => 'disabled')) ?>
				</div>-->
			</div>
			<div class="c50r">
				<div class="row">
					<?php echo CHtml::activeLabelEx($shipment, 'package_weight') ?>
					<?php echo CHtml::activeTextField($shipment, 'package_weight',array('disabled' => 'disabled')) ?>
				</div>
				<div class="row">
					<?php echo CHtml::activeLabelEx($shipment, 'package_value') ?>
					<?php echo CHtml::activeTextField($shipment, 'package_value',array('disabled' => 'disabled')) ?>
				</div>
			</div>
		</div>
	</fieldset>
</div>