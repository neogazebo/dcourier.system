<?php
$this->breadcrumbs = array(
	'User Logs' => array('index'),
	'Manage',
);


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-logs-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

?>

<h1>Manage User Logs</h1>

<p>
	Anda dapat memasukkan operator perbandingan (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
	atau <b>=</b>) pada awal masing-masing nilai pencarian Anda untuk menentukan bagaimana perbandingan harus dilakukan.
</p>
<?PHP /*
  <?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
  <div class="search-form" style="display:none">
  <?php $this->renderPartial('_search',array(
  'model'=>$model,
  )); ?>
  </div><!-- search-form -->
 */

?>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'user-logs-grid',
	'htmlOptions' => array(
		'class' => 'hastable',
	),
	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		array(
			'name' => 'user',
			'value' => '$data->user->username'
		),
		array(
			'name' => 'time',
			'value'=>'Yii::app()->dateFormatter->formatDateTime($data->time, "long")',
		),
		'type',
		'message',
		array(
			'class' => 'CButtonColumn',
		),
	),
));

?>
