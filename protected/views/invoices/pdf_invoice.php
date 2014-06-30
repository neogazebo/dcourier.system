<style type="text/css">
	table.price th,table.price td{
		padding: 10px;
	}
	table.price td.br{
		line-height: 25px;
	}
</style>
<h4>INVOICE</h4>

<table>
	<tr>
		<td>Account Number</td>
		<td>:</td>
		<td><?php echo $customer->accountnr ?></td>
	</tr>
	<tr>
		<td>Account Name</td>
		<td>:</td>
		<td><?php echo $customer->getContactData()->full_name ?></td>
	</tr>
	<tr>
		<td>Address</td>
		<td>:</td>
		<td><?php echo $customer->getContactData()->address ?></td>
	</tr>
</table>

<br />

<table cellpadding="10" border="1" class="price">
	<tr>
		<th width="15%">Shipper</th>
		<th width="15%">Consignee</th>
		<th width="10%">Ship Date</th>
		<th width="20%">Orig</th>
		<th width="20%">Dest</th>
		<th width="10%">Price</th>
		<th width="10%">Name</th>
	</tr>
<?php foreach ($invoice_transaction as $data): ?>
	<tr>
			<td><?php echo $data->getShipmentData()->shipper_name; ?></td>
			<td><?php echo $data->getShipmentData()->receiver_name . '<br />' . $data->getShipmentData()->receiver_address ?></td>
			<td><?php echo Yii::app()->dateFormatter->formatDateTime($data->getShipmentData()->delivery_date, 'medium', null) ?></td>
			<td>
				<?php
				if($data->getShipmentData()->service_type == 'domestic' || $data->getShipmentData()->service_type == 'city')
					echo $data->getShipmentData()->shipper_city;
				 else if($data->getShipmentData()->service_type =='international')
					echo $data->getShipmentData()->shipper_country;
				?>
			</td>
			<td>
				<?php
				if($data->getShipmentData()->service_type == 'domestic' || $data->getShipmentData()->service_type == 'city')
					echo $data->getShipmentData()->receiver_city;
				 else if($data->getShipmentData()->service_type =='international')
					echo $data->getShipmentData()->receiver_country;
				?>
			</td>
			<td class="br">
				<?php
				$print = '';
				$charges = $data->getShipmentCharges();

				foreach ($charges as $charge)
				{
					$print .= 'Rp' . number_format($charge['cost'], 2, ',', '.') . '<br />';
				}

				$print .= '(Rp' . number_format($data->charges - $data->total, 2, ',', '.') . ')<br />';
				$print .= '<strong>Rp' . number_format($data->total, 2, ',', '.') . '</strong>';
				echo $print;

				?>
			</td>
			<td class="br">
				<?php
				$charges = $data->getShipmentCharges();
				$print = '';
				foreach ($charges as $charge)
				{
					$print .= $charge['name'] . '<br />';
				}

				$print .= '(Discount)<br /><strong>Sub Total</strong>';

				echo $print;

				?>
			</td>
		</tr>
<?php endforeach; ?>
		<tr>
			<td colspan="5"><strong>Total Ammount</strong></td>
			<td colspan="2"><strong><?php echo 'Rp' . number_format($total_amount, 2, ',', '.') ?></strong></td>
		</tr>
</table>



<?php
//$this->widget('zii.widgets.grid.CGridView', array(
//	'dataProvider' => $invoice_transaction,
//	'htmlOptions' => array('style' => '"width : 100%"'),
//	'columns' => array(
//		array(
//			'header' => 'Shipper',
//			'type' => 'raw',
//			'value' => function ($data, $row)
//			{
//				return $data->getShipmentData()->shipper_name;
//			},
//		),
//		array(
//			'header' => 'Consignee',
//			'type' => 'raw',
//			'value' => function($data, $row)
//			{
//				return $data->getShipmentData()->receiver_name . '<br />' . $data->getShipmentData()->receiver_address;
//			},
//		),
//		array(
//			'header' => 'Ship Date',
//			'type' => 'raw',
//			'value' => function($data, $row)
//			{
//				return Yii::app()->dateFormatter->formatDateTime($data->getShipmentData()->delivery_date, 'medium', null);
//			}
//		),
//		array(
//			'header' => 'Orig',
//			'type' => 'raw',
//			'value' => function($data, $row)
//			{
//				switch ($data->getShipmentData()->service_type)
//				{
//					case ('domestic' || 'city') :
//						return $data->getShipmentData()->shipper_city;
//						break;
//					case 'international':
//						return $data->getShipmentData()->shipper_country;
//						break;
//				}
//			}
//		),
//		array(
//			'header' => 'Dest',
//			'type' => 'raw',
//			'value' => function($data, $row)
//			{
//				switch ($data->getShipmentData()->service_type)
//				{
//					case ('domestic' || 'city') :
//						return $data->getShipmentData()->receiver_city;
//						break;
//					case 'international':
//						return $data->getShipmentData()->reciver_country;
//						break;
//				}
//			}
//		),
//		array(
//			'header' => 'Price',
//			'type' => 'raw',
//			'footer' => $invoice_transaction->itemcount === 0 ? 'Rp0' : 'Rp' . number_format($total_amount, 2, ',', '.'),
//			'value' => function($data, $row)
//			{
//				$print = '';
//				$charges = $data->getShipmentCharges();
//
//				foreach ($charges as $charge)
//				{
//					$print .= 'Rp' . number_format($charge['cost'], 2, ',', '.') . '<br />';
//				}
//
//				$print .= '(Rp' . number_format($data->charges - $data->total, 2, ',', '.') . ')<br />';
//				$print .= '<strong>Rp' . number_format($data->total, 2, ',', '.') . '</strong>';
//				return $print;
//			}
//		),
//		array(
//			'header' => 'Name',
//			'type' => 'raw',
//			'footer' => 'Total Ammount',
//			'value' => function($data, $row)
//			{
//				$charges = $data->getShipmentCharges();
//				$print = '';
//				foreach ($charges as $charge)
//				{
//					$print .= $charge['name'] . '<br />';
//				}
//
//				$print .= '(Discount)<br /><strong>Sub Total</strong>';
//
//				return $print;
//			}
//		),
//	),
//));

?>