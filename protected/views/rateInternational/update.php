<?php
$this->breadcrumbs=array(
	'International Rates'=>array('index'),
	'Update',
);

$this->menu=array(
	array('label'=>'List International Rates', 'url'=>array('index')),
	array('label'=>'Create International Rates', 'url'=>array('create')),
);
?>

<h4 class="ui-box-header ui-corner-all">Update International Rate</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>