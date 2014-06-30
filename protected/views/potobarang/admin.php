<?php
/* @var $this PotobarangController */
/* @var $model PotoBarang */

$this->breadcrumbs = array(
	'Poto Barangs' => array('index'),
	'Manage',
);

$this->menu = array(
	array('label' => 'List PotoBarang', 'url' => array('index')),
	array('label' => 'Create PotoBarang', 'url' => array('create')),
);

?>

<h1>Manage Poto Barangs</h1>

<p>
	You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
	or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php ?>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'poto-barang-grid',
	'dataProvider' => $model->search(),
	'htmlOptions'=>  array('class' => 'hastable'),
	'filter' => $model,
	'columns' => array(
		array(
			'type' => 'raw',
			'value' => 'CHtml::image(CHtml::encode($data->image), "", array("width"=>"150px"))',
		),
		array(
			'name'=>'user.username'
		),
		'awb',
		'shipment_id',
		array(
			'name' => 'time',
			'type' => 'datetime',
			'filter' => false,
		),
		array(
			'class' => 'CButtonColumn',
		),
	),
));

?>
