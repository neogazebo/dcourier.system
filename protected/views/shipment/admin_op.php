<?php
$this->breadcrumbs = array(
	'Operation' => array('admin'),
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
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('shipment-grid', {
		data: $(this).serialize()
	});
	return false;
});
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
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'shipment-grid',
	'dataProvider' => $model->search(),
//	'filter' => $model,
	'htmlOptions' => array('class' => 'hastable'),
	'columns' => array(
		array(
			'name' => 'id',
			'type' => 'raw',
			'value' => function($data, $row)
			{
				return CHtml::link($data->id, Yii::app()->createUrl('ordertracking/trackingDetails', array('id' => $data->id)));
			}
		),
//		'booking_code',
		'shipper_name',
		'receiver_name',
		array(
			'header' => 'Pieces',
			'type'=>'raw',
			'value'=>function($data,$row){
				return $data->pieces;
			}
		),
		array(
			'header' => 'Weight',
			'type'=>'raw',
			'value'=>function($data,$row){
				return $data->package_weight;
			}
		),
		array(
			'header' => 'Origin Routing Code',
			'name' => 'origin_code'
		)
		)));

?>
<br />
<?php echo CHtml::link('Back', Yii::app()->createUrl('/booking'), array('class'=>'l-btn')) ?>
