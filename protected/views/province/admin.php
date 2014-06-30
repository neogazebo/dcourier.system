<?php
$this->breadcrumbs = array(
	'Provinces' => array('admin'),
	'Manage',
);

$this->menu = array(
	array('label' => 'Create Province', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "

$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('province-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
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
	Manage Provinces
</h4>

<p>
	Filter
</p>

<div class="search-form" >
	<?php
	$this->renderPartial('_search', array(
		'model' => $model,
	));

	?>
</div><!-- search-form -->

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'province-grid',
	'dataProvider' => $model->search(),
	'htmlOptions' => array('class' => 'hastable'),
	'columns' => array(
		'id',
		array(
			'name' => 'name',
			'type' => 'raw',
			'value' => 'CHtml::link($data->name,Yii::app()->createUrl(\'province/view\',array(\'id\' => $data->id)))',
		),
		array(
			'class' => 'CButtonColumn',
			'template' => '{update}{delete}'
		),
	),
));

?>
