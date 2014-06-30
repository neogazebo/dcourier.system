<?php
$this->breadcrumbs = array(
	'Order Tracking' => array('index'),
	'Update Tracking',
);

$this->menu = array(
	array('label' => 'Order Tracking', 'url' => array('index')),
);

$flashMessages = Yii::app()->user->getFlashes();
if ($flashMessages)
{
	foreach ($flashMessages as $key => $message)
	{
		echo '<div class="flash-' . $key . '">' . $message . "</div>";
	}
}
?>

<h4 class="ui-box-header ui-corner-all">
	Order Tracking Detail
</h4>
<br />

<div id="shipment-data-container">
<?php  
	$this->renderPartial('_shipment_data',array('shipment'=>$shipment,'customer'=>$customer));
?>
</div>
<div id="shipment-form-container" style="display: none">
<?php  
	$this->renderPartial('_shipment_form',array('shipment'=>$shipment,'customer'=>$customer));
?>
</div>
<br />
<?php echo CHtml::link('Edit', '#', array('id' => 'togle_form')) ?>
<script type="text/javascript">
	$('#togle_form').click(function(event){
		event.preventDefault();
		$('#shipment-data-container').toggle();
		$('#shipment-form-container').toggle();
	});
</script>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'tracking-grid',
	'dataProvider' => $list_event,
	'htmlOptions' => array('class' => 'hastable'),
	'columns' => array(
		array(
			'header' => 'Date',
			'name' => 'event_time',
			'type' => 'raw',
			'value' => function($data, $row)
			{
				return Yii::app()->dateFormatter->formatDateTime($data->event_time, 'medium', 'short');
			}
		),
		array(
			'name' => 'status',
			'type' => 'raw',
			'value' => function($data, $row)
			{
				if($data->with_mde)
					return $data->status_name->status.', MDE';
				else
					return $data->status_name->status;
			}
		),
		array(
			'name' => 'remark',
			'type' => 'raw',
			'value' => function($data, $row)use($shipment)
			{
				if(($data->remark!= '' || !empty($data->remark)) && $data->status != 10)
					return $data->remark_name->name;
				else if($data->status == 10)
					return $shipment->recipient_name .'/'.$shipment->recipient_title.'<br />'. Yii::app()->dateFormatter->formatDateTime($shipment->recipient_date, 'medium', 'short');
			}
		),
		array(
			'header' => 'Notes',
			'type' => 'raw',
			'name' => 'title',
			'value' => function($data, $row)
			{
				return '<h4>' . $data->title . '</h4>' . $data->description;
			}
		),
		array(
			'header' => 'Last updated by',
			'name' => 'user_id',
			'type' => 'raw',
			'value' => function($data, $row)
			{
				return ucfirst($data->user->username).' ('.Yii::app()->dateFormatter->formatDateTime($data->created, 'medium', 'short').')';
			}
		)
	),
));

?>

<?php echo $this->renderPartial('_form', array('new_event' => $new_event, 'shipment' => $shipment)); ?>

<?php
$ajax = CHtml::ajax(array(
	'url' => array('selectRemarks'),
	'dataType' => 'html',
	'type' => 'post',
	'data' => 'js: {status_id:$(this).val()} ',
	'success' => 'function(data){
		$("#rm").children().remove();
		$("#rm").append(data);
		return true;
	}'
));
$cs = Yii::app()->clientScript;
$script = <<<SCRIPT
    $('#ShipmentEvent_status').live('change',function(){ $ajax });
SCRIPT;
$cs->registerScript('change_status', $script);
?>