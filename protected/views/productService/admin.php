<?php
$this->breadcrumbs=array(
	'Products'=>array('product/index'),
	$model->product->name.' Services',
);

$this->menu=array(
	array('label'=>'Create '.$model->product->name.' Service', 'url'=>array('create','product_id' => $model->product->id)),
);
?>

<h1>Manage <?php echo $model->product->name ?> Services</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'product-service-grid',
	'htmlOptions' => array('class' => 'hastable'),
	'dataProvider'=>$model->search(),
	'columns'=>array(
		'name',
		'code',
		'desc',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{delete}{update}',
		),
	),
)); ?>