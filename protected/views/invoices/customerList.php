<?php
$this->breadcrumbs = array(
	'Customers' => array('admin'),
	'Manage',
);

$this->menu = array(
	array('label' => 'Create Customer', 'url' => array('create')),
);

?>

<h4 class="ui-box-header ui-corner-all">
	Customer List
</h4>
<br />

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
			'value' => function($data,$row)
			{
				return CHtml::link($data->name,yii::app()->createUrl('invoices/viewInvoice',array('id'=>$data->id)));
			}
		),
		'accountnr',
		'type',
	),
));

?>
