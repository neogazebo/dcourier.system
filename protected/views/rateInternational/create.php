<?php
$this->breadcrumbs=array(
	'Internationals Rates'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List International Rates', 'url'=>array('index')),
);
?>

<h4 class="ui-box-header ui-corner-all">Add International Rates</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>