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
			'value' =>function($data,$row)use($redirect,$model){
				return CHtml::link($data->user->username, array('booking/'.$redirect,'id'=>$model->id,'driver_id'=>$data->user_id));
			}
		),
		'routing_code',
		array(
			'header' =>'Assignment',
			'type'=>'raw',
			'value'=>function($data,$row){
				return Booking::model()->count('driver_id=:driver_id',array(':driver_id'=>$data->user_id));
			}
		)
	),
));
?>

<br />
<?php echo CHtml::link('Back', Yii::app()->createUrl('/booking'), array('class'=>'l-btn')) ?>