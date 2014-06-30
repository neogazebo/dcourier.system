<?php
$this->breadcrumbs = array(
	'Companies' => array('index'),
	'Manage',
);

$this->menu = array(
	array('label' => 'Create New Company', 'url' => array('create')),
);

?>

<h4 class="ui-box-header ui-corner-all">
	Manage Company
</h4>
<br />

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'rate-company-grid',
	'ajaxUpdate' => false,
	'dataProvider' => $model->search(),
	'htmlOptions' => array('class' => 'hastable'),
	'columns' => array(
		array(
			'name'=>'name',
			'type'=>'raw',
			'value'=>function($data,$row){
				return CHtml::link($data->name,Yii::app()->createUrl('rateCompanyService',array('rate_company_id' => $data->id)));
			}
			),
		'code',
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
