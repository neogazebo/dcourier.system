<?php
$this->breadcrumbs = array(
		'Province' => array('province/admin'),
		$model->zone->district->province->name => array('province/view', 'id' => $model->zone->district->province_id),
		$model->zone->district->name => array('district/view', 'id' => $model->zone->district_id),
		$model->zone->name => array('zone/view', 'id' =>$model->zone->id),
		'Create'
);

$this->menu=array(
	array('label'=>'List Area', 'url'=>array('zone/view','id'=>$model->zone_id)),
);
?>

<h4 class="ui-box-header ui-corner-all">
	Create Area
</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>