<?php
$this->breadcrumbs=array(
	'States'=>array('admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create State', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('state-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage States</h1>

<p>
Filter
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'state-grid',
	'dataProvider'=>$model->search(),
	'htmlOptions'=>array('class'=>'hastable'),
	'columns'=>array(
		'id',
		'name',
		array(
			'class'=>'CButtonColumn',
				
		),
	),
)); ?>
