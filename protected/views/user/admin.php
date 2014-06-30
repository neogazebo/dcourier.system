<?php
$this->breadcrumbs = array(
    'Users' => array('index'),
    'Manage',
);

$this->menu=array(
	array('label'=>'Add User', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
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
	Manage User
</h4>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link(Yii::t('search','Advanced Search'), '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->
<!--<div><?php // echo CHtml::ajaxLink(Yii::t('crud','Create ').'User', Yii::app()->createUrl('user/create',array('asDialog'=>'1')),array('update'=>'#id_view')); ?></div>-->
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'user-grid',
    'dataProvider' => $model->search(),
		'htmlOptions'=>array('class'=>'hastable'),
    //'filter'=>$model,
    'columns' => array(
        'id',
        'username',
        'email',
        array(
            'name' => 'created',
            'value' => 'Yii::app()->getDateFormatter()->formatDateTime($data->created)',
            'type' => 'raw',
        ),
        array(
            'name' => 'access',
            'header' => Yii::t('application', 'Last Login'),
            'value' => '$data->access!=""?Yii::app()->getDateFormatter()->formatDateTime($data->access):""',
            'type' => 'raw',
        ),
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'dialog_id',
    'options' => array(
        'title' => 'Detail view',
        'autoOpen' => false, //important!
        'modal' => false,
        'width' => '500px',
        'height' => 'auto',
    ),
));
?>
<div id="id_view"></div>
<?php $this->endWidget(); ?>
