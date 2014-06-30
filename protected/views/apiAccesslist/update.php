<?php
/* @var $this ApiAccesslistController */
/* @var $model ApiAccesslist */

$this->breadcrumbs=array(
	'Api Accesslists'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
//	array('label'=>'List ApiAccesslist', 'url'=>array('index')),
//	array('label'=>'Create ApiAccesslist', 'url'=>array('create')),
//	array('label'=>'View ApiAccesslist', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ApiAccesslist', 'url'=>array('admin','customer_id' => $model->customer_id)),
);
?>

<h1>Update ApiAccesslist <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>