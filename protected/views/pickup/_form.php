<?php
$redirect = $this->getAction()->id;
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'assign_courier-grid',
	'dataProvider' => $driver_list_available,
	'htmlOptions' => array('class' => 'hastable'),
	'columns' => array(
		array(
			'header'=>'Driver Name',
			'type'=>'raw',
			'name'=>'user.username',
			'value' =>function($data,$row)use($shipment_id,$redirect){
				return CHtml::link($data->user->username, array('pickup/'.$redirect,'driver_id'=>$data->user_id,'shipment_id'=>$shipment_id), array('class'=>'assign','id'=>$data->user_id));
			}
		),
		'routing_code',
		array(
			'header' =>'Assignment',
			'type'=>'raw',
			'value'=>function($data,$row)use($shipment_id){
				return Pickup::model()->with(array('shipment'=>array('condition'=>'shipping_status=11')))->count('driver_id=:driver_id',array(':driver_id'=>$data->user_id));
			}
		)
	),
));

?>