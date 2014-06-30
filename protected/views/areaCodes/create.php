<?php
$this->breadcrumbs=array(
	'Area Codes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AreaCodes', 'url'=>array('index')),
	array('label'=>'Manage AreaCodes', 'url'=>array('admin')),
);
?>

<h1>Create AreaCodes</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>