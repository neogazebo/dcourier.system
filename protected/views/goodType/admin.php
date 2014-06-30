<?php
$this->breadcrumbs=array(
	'Good Types'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create GoodType', 'url'=>array('create')),
);
?>

<h4 class="ui-box-header ui-corner-all">
	Manage Good Type
</h4>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'good-type-grid',
	'dataProvider'=>$model->search(),
	'htmlOptions' => array('class' => 'hastable'),
	'columns'=>array(
		'code',
		'desc',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}'
		),
	),
)); ?>
