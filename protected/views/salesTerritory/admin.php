<?php
/* @var $this SalesTeritoryController */
/* @var $model SalesTeritory */

$this->breadcrumbs=array(
	'Sales Teritories'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create SalesTeritory', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('sales-teritory-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Sales Teritories</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button','style' => 'display:none')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sales-teritory-grid',
	'htmlOptions'=>array('class'=>'hastable'),
	'dataProvider'=>$model->search(),
	'columns'=>array(
		'territory',
		array(
			'header' => 'Sales Name',
			'name'=>'user_id',
			'type'=>'raw',
			'value'=>function($data,$row){
				return ucfirst($data->user->username);
			}
		),
		array(
			'header' => 'Email',
			'type'=>'raw',
			'value'=>function($data,$row){
				return $data->user->email;
			}
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}'
		),
	),
)); ?>
