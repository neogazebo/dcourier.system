<?php
$this->breadcrumbs = array(
	'Customers' => array('admin'),
	'Manage',
);

$this->menu = array(
	array('label' => 'Create Customer', 'url' => array('create')),
);

$flashMessages = Yii::app()->user->getFlashes();
if ($flashMessages)
{
	foreach ($flashMessages as $key => $message)
	{
		echo '<div class="flash-' . $key . '">' . $message . "</div>";
	}
}

?>

<h4 class="ui-box-header ui-corner-all">
	Manage Customer
</h4>
<br />
<div class="search-form">
	<?php
	$this->renderPartial('_search', array(
		'model' => $model,
	));

	?>
</div><!-- search-form -->

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'customer-grid',
	'dataProvider' => $model->search(),
	'ajaxUpdate' => false,
	'htmlOptions' => array('class' => 'hastable'),
	'columns' => array(
		array(
			'name' => 'name',
			'type' => 'raw',
			'value' => 'CHtml::link($data->name,Yii::app()->createUrl(\'customer/view\',array(\'id\'=>$data->id)))',
		),
		'accountnr',
		'type',
		array(
			'name' => 'Address',
			'type' => 'raw',
			'value' => function($data, $row){
				return $data->getContactData()->address;
			}
		),
		array(
			'name' => 'Phone',
			'type' => 'raw',
			'value' => function($data,$row){
				return $data->getContactData()->phone1;
			}
		),
		array(
			'name' => 'Email',
			'type' => 'raw',
			'value' => function($data,$row){
				return $data->getContactData()->email;
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
