<?php
$this->breadcrumbs=array(
	'Province' => array('province/admin'),
		$model->district->province->name => array('province/view', 'id' => $model->district->province_id),
		$model->district->name=>array('district/view', 'id' => $model->district_id),
		'Update '.$model->name,
);

$this->menu=array(
	array('label'=>'Manage Zone', 'url'=>array('district/view', 'id' => $model->district_id)),
);
?>

<h1>Update Zone <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>