<?php
$this->breadcrumbs=array(
	'Companies'=>array('rateCompany/index'),
	$model->rateCompanyService->rateCompany->name.' Services'=>array('rateCompanyService/index','rate_company_id'=>$model->rateCompanyService->rateCompany->id),
	'Service Detail',
);

$this->menu=array(
	array('label'=>'Create ServiceDetail', 'url'=>array('create','company_service_id'=>$model->rate_company_service_id)),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('service-detail-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Service Details</h1>

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
	'id'=>'service-detail-grid',
	'dataProvider'=>$model->search(),
	'htmlOptions'=>array('class' => 'hastable'),
	'columns'=>array(
		'id',
		array(
			'header'=>'Product',
			'type'=>'raw',
			'value'=>function($data,$row){
				return $data->productService->product->name;
			}
		),
		array(
			'header'=>'Product Service',
			'type'=>'raw',
			'value'=>function($data,$row){
				return $data->productService->name.' / '.$data->productService->code;
			}
		),
		array(
			'header'=>'Product',
			'type'=>'raw',
			'value'=>function($data,$row){
				return $data->rateCompanyService->name;
			}
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}'
		),
	),
)); ?>
