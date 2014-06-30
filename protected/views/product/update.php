<?php
$this->breadcrumbs=array(
	'Products'=>array('index'),
	'Update '.$model->name,
);

$this->menu=array(
	array('label'=>'Manage Product', 'url'=>array('index')),
);
?>

<h1>Update <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>