<?php
$this->breadcrumbs=array(
	'Origins'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Origins', 'url'=>array('index')),
	array('label'=>'Create Origins', 'url'=>array('create')),
	array('label'=>'Update Origins', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Origins', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Origins', 'url'=>array('admin')),
);
?>

<h1>View Origins #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
        'htmlOptions'=>array('class'=>'hastable'),
        'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
                array(
                  'name'=>'created',
                  'value'=>Yii::app()->dateFormatter->formatDateTime($model->created, "full"),
                  'type'=>'raw'
                ),
		array(
                  'name'=>'updated',
                   'value'=>Yii::app()->dateFormatter->formatDateTime($model->created, "full"),
                ),
	),
)); ?>
