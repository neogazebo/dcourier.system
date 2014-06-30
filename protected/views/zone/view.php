<?php
$this->breadcrumbs = array(
	'Province' => array('province/admin'),
	$model->district->province->name => array('province/view', 'id' => $model->district->province_id),
	$model->district->name => array('district/view', 'id' => $model->district_id),
	$model->name,
);

$this->menu = array(
	array('label' => 'List Zone', 'url' => array('district/view', 'id' => $model->district_id)),
	array('label' => 'Create Area', 'url' => array('area/create', 'id' => $model->id)),
);

?>

<h4 class="ui-box-header ui-corner-all">
	Zone <?php echo $model->name; ?>
</h4>
<br />
<?php
$this->widget('zii.widgets.CDetailView', array(
	'data' => $model,
	'attributes' => array(
		'id',
		array(
			'name' => 'district_id',
			'value' => $model->district->name,
		),
	),
));

?>
<br />
<h1> List Area</h1>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'area-grid',
	'dataProvider' => $area,
	'htmlOptions' => array('class' => 'hastable'),
	'columns' => array(
		'id',
		'name',
		'postcode',
		array(
			'class' => 'CButtonColumn',
			'template' => '{update}{delete}',
			'buttons' => array(
				'update' => array(
					'url' => 'Yii::app()->createUrl("area/update",array("id"=>$data->id))',
				),
				'delete' => array(
					'url' => 'Yii::app()->createUrl("area/delete",array("id"=>$data->id))',
				),
			),
		),
	),
));

?>
