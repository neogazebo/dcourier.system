<?php
$this->breadcrumbs = array(
	'Customer Service' => array('customerService'),
	'Manage',
);

$this->menu = array(
	array('label' => 'Create Shipment', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
//$('.search-form form').submit(function(){
//	$.fn.yiiGridView.update('shipment-grid', {
//		data: $(this).serialize()
//	});
//	return false;
//});
");

?>

<h4 class="ui-box-header ui-corner-all">
	Manage Shipments
</h4>

<p>
	You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
	or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
	<?php
	$this->renderPartial('_search', array(
		'model' => $model,
	));

	?>
</div><!-- search-form -->

<?php
//$this->widget('zii.widgets.grid.CGridView', array(
//	'id' => 'shipment-grid1',
//	'dataProvider' => $model->search('cs-buk'),
////	'filter' => $model,
//	'htmlOptions' => array('class' => 'hastable'),
//	'columns' => array(
//		array(
//			'name' => 'id',
//			'type' => 'raw',
//			'value' => function($data, $row)
//			{
//				return CHtml::link($data->id, Yii::app()->createUrl('ordertracking/trackingDetails', array('id' => $data->id)));
//			}
//		),
//		array(
//			'header' => 'Shipper Details',
//			'type' => 'raw',
//			'value' => function($data, $row)
//			{
//				return $data->shipper_name . '<br />' . $data->shipper_address . '<br />' . $data->shipper_country . '<br />' . $data->shipper_city . '<br />' . $data->shipper_postal;
//			}
//		),
//		array(
//			'header' => 'Receiver Details',
//			'type' => 'raw',
//			'value' => function($data, $row)
//			{
//				return $data->receiver_name . '<br />' . $data->receiver_address . '<br />' . $data->receiver_country . '<br />' . $data->receiver_city . '<br />' . $data->receiver_postal;
//			}
//		),
//		array(
//			'name' => 'shipping_status',
//			'type' => 'raw',
//			'value' => function ($data, $row)
//			{
//				$status = ShipmentStatus::model()->findByPk($data->shipping_status);
//				if (($status instanceof ShipmentStatus))
//					return $status->status;
//			}
//		),
//		array(
//			'name' => 'service_type',
//			'header' => 'Product'
//		),
//		array(
//			'name' => 'created',
//			'value' => '$data->created!=""?Yii::app()->getDateFormatter()->formatDateTime($data->created):""',
//			'type' => 'raw',
//		),
//		)));

?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'shipment-grid1',
	'dataProvider' => $model->search(),
//	'filter' => $model,
	'htmlOptions' => array('class' => 'hastable'),
	'columns' => array(
		'awb',
		array(
			'header' => 'Booking Code',
			'type' => 'raw',
			'value' => function($data,$row){
				if($data->booking instanceof Booking)
				{
					return strtoupper($data->booking->booking_code);
				}
			}
		),
		array(
			'header' => 'Shipper Details',
			'type' => 'raw',
			'value' => function($data, $row)
			{
				return $data->shipper_name;
			}
		),
		array(
			'header' => 'Receiver Details',
			'type' => 'raw',
			'value' => function($data, $row)
			{
				return $data->receiver_name;
			}
		),
		array(
			'name' => 'shipping_status',
			'type' => 'raw',
			'value' => function ($data, $row){
				$status = ShipmentStatus::model()->findByPk($data->shipping_status);
				if (($status instanceof ShipmentStatus))
					return $status->status;
			}
		),
		array(
			'name' => 'service_type',
			'header' => 'Product'
		),
		array(
			'name' => 'created',
			'value' => '$data->created!=""?Yii::app()->getDateFormatter()->formatDateTime($data->created):""',
			'type' => 'raw',
		),
		array(
			'type' => 'raw',
			'value' => function($data,$raw){
				return CHtml::link('order tracking', Yii::app()->createUrl('ordertracking/trackingDetails', array('id' => $data->id)));
			}
		)
		)));

?>
