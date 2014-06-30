<?php
$this->breadcrumbs=array(
	'Bookings'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create Booking', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('booking-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Bookings</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'booking-grid',
	'htmlOptions'=>array('class'=>'hastable'),
	'dataProvider'=>$model->search(),
	'columns'=>array(
		array(
			'header'=>'Created',
			'type'=>'raw',
			'value'=>function($data,$row){
				return date('d-m-Y',$data->created);
			}
		),
		array(
			'header'=>'boking code',
			'type'=>'raw',
			'value'=>function($data,$raw){
				return CHtml::link(strtoupper($data->booking_code), Yii::app()->createUrl('/shipment/operation',array('booking_id'=>$data->id)));
			}
		),
//		array(
//			'header'=>'Customer',
//			'type'=>'raw',
//			'value'=>function($data,$row){
//				return $data->customer->accountnr.' / '.$data->customer->name;
//			}
//		),
		'request_by',
		/*
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
		*/
		array(
			'type' => 'raw',
			'value' => function($data,$row){
				if($data->driver_id == 0)
					return CHtml::link('not assigned',  Yii::app()->createUrl('booking/assignCourier',array('id' => $data->id)));
				else
					return CHtml::link($data->driver_id,  Yii::app()->createUrl('booking/assignCourier',array('id' => $data->id)));
			}
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}'
		),
	),
)); ?>
