<?php
$this->breadcrumbs = array(
	'Intra City Services' => array('admin'),
	'Update ' . $model->name,
);

$this->menu = array(
	array('label' => 'List Intra City Services', 'url' => array('admin')),
);

?>

<h4 class="ui-box-header ui-corner-all">Update Intra City Services <?php echo $model->name; ?></h4>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>