<?php
$this->breadcrumbs=array(
	'Bookings'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Booking', 'url'=>array('index')),
	array('label'=>'Create Booking', 'url'=>array('create')),
	array('label'=>'Update Booking', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Booking', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Booking', 'url'=>array('admin')),
);
?>

<h1>View Booking #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'created',
		'booking_code',
		'customer_id',
		'name',
		'request_by',
		'address',
		'city',
		'postal',
		'country',
		'pickup_date',
		'shipment_ready_time',
		'office_close_time',
		'address_type',
		'pickup_note',
		'shipment_location',
	),
)); ?>
