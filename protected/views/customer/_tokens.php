<?php

$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'cust-token',
	'dataProvider' => $customer_tokens,
	'ajaxUpdate' => true,
	'htmlOptions' => array('class' => 'hastable'),
	'columns' => array(
		'token',
		'lastaccess',
	),
));

?>