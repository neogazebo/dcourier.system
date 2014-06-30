<?php
/* @var $this DriverController */
/* @var $model Driver */

$this->breadcrumbs=array(
	'Drivers'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create Driver', 'url'=>array('create')),
	array('label'=>'Map', 'url'=>array('map')),
	array('label'=>'Photos', 'url'=>array('potobarang/admin')),
);
?>

<h1>Manage Drivers</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'driver-grid',
	'dataProvider'=>$model->search(),
	'htmlOptions'=>  array('class' => 'hastable'),
	'columns'=>array(
		array(
			'header' => 'Driver',
			'name' => 'user_id',
			'type' => 'raw',
			'value' => function($data,$row){
				$user = User::model()->findByPk($data->user_id);
				return ucfirst($user->username);
			}
		),
		'routing_code',
		'token',
		'message',		
//		array(
//			'class'=>'CButtonColumn',
//		),
	),
)); ?>
