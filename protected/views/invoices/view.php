<?php
$this->breadcrumbs = array(
	'Invoices' => array('index'),
	$model->id,
);

$this->menu = array(
	array('label' => 'List Invoices', 'url' => array('viewInvoice', 'id' => $model->customer_id)),
	array('label' => 'Create Invoices', 'url' => array('create', 'id' => $model->customer_id)),
	array('label' => 'Update Invoices', 'url' => array('update', 'id' => $model->id)),
	array('label' => 'Delete Invoices', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
	array('label' => 'Generate Invoice', 'url' => array('generate','id' => $model->id)),
);

?>

<h4>INVOICE</h4>

<?php
$this->widget('zii.widgets.CListView', array(
	'dataProvider' => $customer,
	'itemView' => '_viewCustomer',
	'summaryText' => '',
));

?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $invoice_transaction,
	'htmlOptions' => array('class' => 'hastable'),
	'columns' => array(
		array(
			'header' => 'Shipper',
			'type' => 'raw',
			'value' => function ($data, $row)
			{
				return $data->getShipmentData()->shipper_name;
			},
		),
		array(
			'header' => 'Consignee',
			'type' => 'raw',
			'value' => function($data, $row)
			{
				return $data->getShipmentData()->receiver_name . '<br />' . $data->getShipmentData()->receiver_address;
			},
		),
		array(
			'header' => 'Ship Date',
			'type' => 'raw',
			'value' => function($data, $row)
			{
				return Yii::app()->dateFormatter->formatDateTime($data->getShipmentData()->delivery_date, 'medium', null);
			}
		),
		array(
			'header' => 'Orig',
			'type' => 'raw',
			'value' => function($data, $row)
			{
				switch ($data->getShipmentData()->service_type)
				{
					case ('domestic' || 'city') :
						return $data->getShipmentData()->shipper_city;
						break;
					case 'international':
						return $data->getShipmentData()->shipper_country;
						break;
				}
			}
		),
		array(
			'header' => 'Dest',
			'type' => 'raw',
			'value' => function($data, $row)
			{
				switch ($data->getShipmentData()->service_type)
				{
					case ('domestic' || 'city') :
						return $data->getShipmentData()->receiver_city;
						break;
					case 'international':
						return $data->getShipmentData()->reciver_country;
						break;
				}
			}
		),
		array(
			'header' => 'Price',
			'type' => 'raw',
			'footer' => $invoice_transaction->itemcount ===0 ? 'Rp0':'Rp'.number_format( $total_amount,2, ',' , '.'),
			'value' => function($data,$row){
				$print = '';
				$charges = $data->getShipmentCharges();

				foreach ($charges as $charge)
				{	
						$print .= 'Rp'.number_format($charge['cost'],2, ',' , '.').'<br />';
				}
				return $print;
				
			}
		),
		array(
			'header' => 'Name',
			'type' => 'raw',
			'footer' => 'Total Ammount',
			'value' => function($data,$row){
				$charges = $data->getShipmentCharges();
				$print = '';
				foreach ($charges as $charge)
				{
						$print .= $charge['name'].'<br />';
				}
				return $print;
			}
		),
	),
));

?>
