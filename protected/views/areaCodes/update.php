<?php
$this->breadcrumbs=array(
	'Area Codes'=>array('index'),
	$model->code=>array('view','id'=>$model->code),
	'Update',
);

$this->menu=array(
	array('label'=>'List AreaCodes', 'url'=>array('index')),
	array('label'=>'Create AreaCodes', 'url'=>array('create')),
	array('label'=>'View AreaCodes', 'url'=>array('view', 'id'=>$model->code)),
	array('label'=>'Manage AreaCodes', 'url'=>array('admin')),
);
?>

<h1>Update AreaCodes <?php echo $model->code; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>