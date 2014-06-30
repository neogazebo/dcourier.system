<?php
/* @var $this ApiAccesslistController */
/* @var $model ApiAccesslist */

$this->breadcrumbs=array(
	'Api Accesslists'=>array('index'),
	'Create',
);

$this->menu=array(
//	array('label'=>'List ApiAccesslist', 'url'=>array('index', 'customer_id' => $customer_id)),
	array('label'=>'Manage ApiAccesslist', 'url'=>array('admin', 'customer_id' => $customer_id)),
);
?>

<h1>Create ApiAccesslist</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'customer_id'=>$customer_id)); ?>