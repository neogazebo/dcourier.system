<?php 
	/** payby style */
	switch ($shipment->pay_by)
	{
		case 'cash':
			$payby_element = CHtml::tag('div', array('style'=> 'position:absolute; top:23;left: 77;font-size: 11px;'), 'v', true);
			break;
		case 'credit_card':
			$payby_element = CHtml::tag('div', array('style'=> 'position:absolute; top:23;left: 106;font-size: 11px;'), 'v', true);
			break;
		case 'account':
			$payby_element =  CHtml::tag('div', array('style'=> 'position:absolute; top:23;left: 157;font-size: 11px;'), 'v', true);
			break;
		default :
			$payby_element = '';
			break;
	}
	
	/** charge to */
	switch ($shipment->payer)
	{
		case 'shipper':
			$charge_to = CHtml::tag('div', array('style'=> 'position:absolute; top:35;left: 83;font-size: 11px;'), 'v', true);
			break;
		case 'consignee':
			$charge_to =  CHtml::tag('div', array('style'=> 'position:absolute; top:35;left: 140;font-size: 11px;'), 'v', true);
			break;
		default :
			$charge_to = '';
			break;
	}
	
	/** service type */
	switch ($shipment->insurance)
	{
		case 1:
			$insurance = CHtml::tag('div', array('style'=> 'position:absolute; top:243;left: 256;font-size: 11px;'), 'v', true);
			break;
		case 0:
			$insurance = '';
			break;
		default :
			$insurance = '';
			break;
	}
	
	/** service type */
	switch ($shipment->service_type)
	{
		case 'City Courier':
			$service_type = CHtml::tag('div', array('style'=> 'position:absolute; top:215;left: 340;font-size: 11px;'), 'v', true);
			break;
		case 'Domestic':
			$service_type = CHtml::tag('div', array('style'=> 'position:absolute; top:230;left: 340;font-size: 11px;'), 'v', true);
			break;
		case 'International':
			$service_type =  CHtml::tag('div', array('style'=> 'position:absolute; top:244;left: 340;font-size: 11px;'), 'v', true);
			break;
		default :
			$service_type =  CHtml::tag('div', array('style'=> 'position:absolute; top:260;left: 340;font-size: 11px;'), 'v', true);
			break;
	}
?>
<div style="position: relative;display: block">
	<div style="width:700px;height:300px;background: url(img/image002.png) no-repeat"></div>
	
	<?php echo $payby_element ?>
	<?php echo $charge_to ?>
	<div id="awb" style="position: absolute;top:40;right:150;font-size:18px">
		<?php echo $shipment->awb?>
	</div>
	<div id="customer_number" style="position:absolute; top:49;left: 76;font-size: 11px">
		<?php echo $customer->accountnr ?>
	</div>
	<div id="destination_code" style="position:absolute; top:20;left: 370;font-size: 11px">
		<?php echo $shipment->destination_code ?>
	</div>
	
	<!-- shipper detail -->
	<div id="shipper_name" style="position:absolute; top:100;left: 160;font-size: 11px">
		<?php echo $shipment->shipper_name ?>
	</div>
	<div id="shipper_address" style="position:absolute; top:136;left: 6;font-size: 11px;width: 320px">
		<?php echo $shipment->shipper_address ?>
	</div>
	<div id="shipper_phone" style="position:absolute; top:125;left: 175;font-size: 9px;">
		<?php echo $shipment->shipper_phone ?>
	</div>
	<div id="shipper_city" style="position:absolute; top:178;left: 20;font-size: 8px;">
		<?php echo $shipment->shipper_city ?>
	</div>
	<div id="shipper_country" style="position:absolute; top:189;left: 28;font-size: 8px;">
		<?php echo $shipment->shipper_country ?>
	</div>
	<div id="shipper_postal" style="position:absolute; top:175;left: 190;font-size: 8px;">
		<?php echo $shipment->shipper_postal ?>
	</div>
	
	<!-- receiver detail -->
	<div id="receiver_name" style="position:absolute; top:100;left: 482;font-size: 11px">
		<?php echo $shipment->receiver_name ?>
	</div>
	<div id="receiver_address" style="position:absolute; top:136;left: 335;font-size: 11px;width: 320px">
		<?php echo $shipment->receiver_address ?>
	</div>
	<div id="receiver_phone" style="position:absolute; top:125;left: 500;font-size: 9px;">
		<?php echo $shipment->receiver_phone ?>
	</div>
	<div id="receiver_city" style="position:absolute; top:176;left: 349;font-size: 8px;">
		<?php echo $shipment->receiver_city ?>
	</div>
	<div id="receiver_country" style="position:absolute; top:189;left: 354;font-size: 8px;">
		<?php echo $shipment->receiver_country ?>
	</div>
	<div id="receiver_postal" style="position:absolute; top:176;left: 511;font-size: 8px;">
		<?php echo $shipment->receiver_postal ?>
	</div>
	
	<!-- shipment detail -->
	<div id="pieces" style="position:absolute; top:225;left: 6;">
		<?php echo $shipment->pieces ?>
	</div>
	<div id="package_weight" style="position:absolute; top:225;left: 80;font-size: 10px">
		<?php echo number_format($shipment->package_weight, 0) ?>
	</div>
	<div id="goods_desc" style="position:absolute; top:217;left: 153;font-size: 10px;width: 178px;">
		<?php echo $shipment->goods_desc ?>sfsdf
	</div>
	<div id="package_value" style="position:absolute; top:260;left: 153;font-size: 10px;width: 178px;">
		<?php echo number_format($shipment->package_value, 0) ?>
	</div>
	<?php echo $insurance ?>
	
	<!-- product service -->
	<div id="service_code" style="position:absolute; top:230;left: 410;font-size: 10px;">
		<?php echo $shipment->service_code ?>
	</div>
	<?php echo $service_type ?>
</div>