<?php
/* @var $this ApiAccesslistController */
/* @var $model ApiAccesslist */

$this->breadcrumbs = array(
	'Api Accesslists' => array('index'),
	'Manage',
);

$this->menu = array(
	array('label' => 'Create ApiAccesslist', 'url' => array('create', 'customer_id' => $customer_id)),
	array('label' => 'Back To Customer', 'url' => array('customer/update', 'id' => $customer_id)),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('api-accesslist-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

?>

<h1>Manage Api Accesslists</h1>

<p>
	You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
	or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'api-accesslist-grid',
	'dataProvider' => $model,
//	'filter'=>$model,
	'columns' => array(
		'ip_address',
		'created',
		'user_id',
		array(
			'class' => 'CButtonColumn',
			'buttons' => array(
				'view' => array(
					'visible' => 'false',
				),
			),
		),
	),
));

?>
