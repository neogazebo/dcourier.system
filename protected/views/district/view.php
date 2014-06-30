<?php
$this->breadcrumbs = array(
	'Province' => array('admin'),
	$model->province->name => array('province/view', 'id' => $model->province_id),
	$model->name,
);

$this->menu = array(
	array('label' => 'List District', 'url' => array('province/view', 'id' => $model->province_id),),
	array('label' => 'Create Zone', 'url' => array('zone/create', 'id' => $model->id)),
);

?>

<h4 class="ui-box-header ui-corner-all">
	District <?php echo $model->name; ?>
</h4>
<br />
<?php
$this->widget('zii.widgets.CDetailView', array(
	'data' => $model,
	'attributes' => array(
		'id',
		array(
			'name' => 'province_id',
			'value' => $model->province->name,
		),
	),
));

?>
<br />
<h1>List Zone</h1>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'zone-grid',
	'dataProvider' => $zone,
	'htmlOptions' => array('class' => 'hastable'),
	'columns' => array(
		'id',
		array(
			'name' => 'name',
			'type' => 'raw',
			'value' => 'CHtml::link($data->name,Yii::app()->createUrl(\'zone/view\',array(\'id\'=>$data->id)))',
		),
		array(
			'class' => 'CButtonColumn',
			'template' => '{update}{delete}',
			'buttons' => array(
				'update' => array(
					'url' => 'Yii::app()->createUrl(\'zone/update\',array(\'id\'=>$data->id))',
				),
				'delete' => array(
					'url' => 'Yii::app()->createUrl(\'zone/delete\',array(\'id\'=>$data->id))',
				),
			),
		),
	),
));

?>
