<?php
$this->breadcrumbs=array(
	'Manage',
);

$this->menu=array(
	array('label'=>'List Customer', 'url'=>array('admin')),
	array('label' => 'Manage Customer', 'url' => array('update','id'=>$customer->id)),
);
?>

<h4 class="ui-box-header ui-corner-all">
	Update Rekening for <?php echo $customer->name?>
</h4>

<?php echo $this->renderPartial('_form_rekening', array('rekening' => $rekening,'customer' => $customer,)); ?>