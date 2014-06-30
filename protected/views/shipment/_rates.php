<?php
echo '<p>'.$product.'</p>';
if ($product != 'International'):
	$this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider' => $rates,
		'htmlOptions' => array('class' => 'hastable'),
		'summaryText' => '',
		'enableSorting' => false,
		'columns' => array(
			array(
				'header' => 'Carrier',
				'name' => 'carrier_name'
			),
			array(
				'header' => 'Service Name',
				'name' => 'service_name'
			),
			array(
				'header' => 'Service Code',
				'name' => 'service_code'
			),
			array(
				'header' => 'Price',
				'name' => 'price',
			),
			array(
				'header' => 'Transits day',
				'name' => 'transits_days'
			),
			'service_id',
			'routing_code',
			array(
				'type'=>'raw',
				'value'=>function($data,$row)use($customer_id,$postal,$country,$product,$package_weight){
					return CHtml::link('use rate',Yii::app()->createUrl('shipment/createAWB',array(
						'customer_id'=>$customer_id,
						'Shipment[destination_code]'=>$data['routing_code'],
						'Shipment[service_code]'=>$data['service_code'],
						'Shipment[receiver_postal]'=>$postal,
						'Shipment[receiver_country]'=>$country,
						'Shipment[service_type]'=>$product,
						'Shipment[service_id]'=>$data['service_id'],
						'Shipment[package_weight]'=>$package_weight,
						'Shipment[shipping_charges]'=>$data['price']
						)
					),array('class'=>'use_rate'));
				}
			)
		)
	));
else :
	$this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider' => $rates,
		'htmlOptions' => array('class' => 'hastable'),
		'summaryText' => '',
		'enableSorting' => false,
		'columns' => array(
			array(
				'header' => 'Carrier',
				'name' => 'carrier_name'
			),
			array(
				'header' => 'Service Name',
				'name' => 'service_name'
			),
			array(
				'header' => 'Service Code',
				'name' => 'service_code'
			),
			array(
				'header' => 'Price',
				'name' => 'price',
			),
			array(
				'header' => 'Transits day',
				'name' => 'transits_days'
			),
			array(
				'header' => 'Shipment Type',
				'name' => 'package_type'
			),
		)
	));
endif;

?>