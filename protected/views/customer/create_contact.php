<?php
$this->breadcrumbs=array(
	'Contact'=>array('contact','id'=>$id),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Customer', 'url'=>array('admin')),
	array('label' => 'Manage Customer', 'url' => array('update','id'=>$id)),
);
?>

<h4 class="ui-box-header ui-corner-all">
	Create Contact for <?php echo $customer_name?>
</h4>

<?php echo $this->renderPartial('_form_contact', array('customerContact'=>$customerContact,'contact'=>$contact)); ?>