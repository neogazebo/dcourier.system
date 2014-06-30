<?php

$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'cust-shipping-profile',
	'dataProvider' => $customer_shipping_profile,
	'ajaxUpdate' => true,
	'htmlOptions' => array('class' => 'hastable'),
	'columns' => array(
		array(
			'name' => 'Product Service',
			'type' => 'raw',
			'value' => function($data,$row){
				return $data->productService->name;
			}
		),
		array(
			'name' => 'Product',
			'type' => 'raw',
			'value' => function($data,$row){
				return $data->productService->product->name;
			}
		),
		'origin',
		'destination',
		'volume'
	),
));

?>