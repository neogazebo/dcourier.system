<?php
$this->breadcrumbs=array(
	'Intra City Areas'=>array('admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Add Intra City Area', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('intra-city-areas-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h4 class="ui-box-header ui-corner-all">Manage Intra City Areas</h4>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'intra-city-areas-grid',
	'dataProvider'=>$model->search(),
	'htmlOptions'=>array('class'=>'hastable'),
	'columns'=>array(
		'id',
		'name',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{delete}{update}'
		),
	),
)); ?>
