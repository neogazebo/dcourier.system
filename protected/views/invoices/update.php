<?php
$this->breadcrumbs=array(
	'Invoices'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Invoices', 'url'=>array('viewInvoice','id' => $customer->id)),
);
?>

<h4 class="ui-box-header ui-corner-all">Update Invoices</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model,'customer'=>$customer)); ?>