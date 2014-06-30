<?php
$this->breadcrumbs = array(
	'Companies' => array('rateCompany/index'),
	$model->rateCompany->name. ' Services',
);

$this->menu = array(
	array('label' => 'Create '.$model->rateCompany->name.' Service', 'url' => array('create', 'rate_company_id' => $model->rateCompany->id)),
);

?>

<h1>Manage <?php echo $model->rateCompany->name ?> Services</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'rate-company-service-grid',
	'dataProvider' => $model->search(),
	'htmlOptions' => array('class' => 'hastable'),
	'columns' => array(
		array(
			'name' => 'name',
			'type'=> 'raw',
			'value'=> function($data,$row){
				return CHtml::link($data->name, Yii::app()->createUrl('serviceDetail', array('company_service_id'=>$data->id)));
			}
		),
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
