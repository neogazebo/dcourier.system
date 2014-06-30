<?php
$this->breadcrumbs = array(
	'Provinces' => array('admin'),
	$model->name,
);

$this->menu = array(
	array('label' => 'Manage Province', 'url' => array('admin')),
	array('label' => 'Create District', 'url' => array('district/create', 'id' => $model->id)),
);

?>

<h4 class="ui-box-header ui-corner-all">
	Province <?php echo $model->name; ?>
</h4>
<p>District List</p>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'district-grid',
	'dataProvider' => $district,
	'htmlOptions' => array('class' => 'hastable'),
	'columns' => array(
		'id',
		array(
			'name' => 'name',
			'type' => 'raw',
			'value' => 'CHtml::link(ucfirst($data->type).\' \'.$data->name,Yii::app()->createUrl(\'district/view\', array(\'id\'=>$data->id)))',
		),
		array(
			'class' => 'CButtonColumn',
			'template' => '{update}{delete}',
			'buttons' => array(
				'update' => array(
					'url' => 'Yii::app()->createUrl(\'district/update\',array(\'id\'=>$data->id))',
				),
				'delete' => array(
					'url' => 'Yii::app()->createUrl(\'district/delete\',array(\'id\'=>$data->id))',
				),
			),
		),
	),
));

?>