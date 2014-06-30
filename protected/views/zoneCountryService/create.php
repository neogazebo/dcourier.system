<?php
$this->breadcrumbs=array(
	'Zone Country Services'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Zone Country', 'url'=>array('admin')),
);
?>

<h4 class="ui-box-header ui-corner-all">Create Zone Country</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>