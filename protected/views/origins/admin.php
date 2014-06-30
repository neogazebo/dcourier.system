<?php
$this->breadcrumbs=array(
	'Origins'=>array('admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create Origins', 'url'=>array('create')),
);
?>

<h4 class="ui-box-header ui-corner-all">Manage Origins</h4>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'origins-grid',
	'dataProvider'=>$model->search(),
	'htmlOptions' => array('class' => 'hastable'),
	'columns'=>array(
		'id',
		'name',
		array(
                  'name'=>'created',
                   'value'=>'Yii::app()->dateFormatter->formatDateTime($data->created, "full")',
                ),
		array(
                  'name'=>'updated',
                   'value'=>'Yii::app()->dateFormatter->formatDateTime($data->updated, "full")',
                ),
		array(
			'class'=>'CButtonColumn',
			'template' => '{update}{delete}',
		),
	),
)); ?>
