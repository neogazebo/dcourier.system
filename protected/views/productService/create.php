<?php
/* @var $this ProductServiceController */
/* @var $model ProductService */

$this->breadcrumbs=array(
	'Products'=>array('product/index'),
	$model->product->name.' Services'=>array('index','product_id'=>$model->product_id),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage '.$model->product->name.' Service', 'url'=>array('index','product_id'=>$model->product_id)),
);
?>

<h1>Create Product Service</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>