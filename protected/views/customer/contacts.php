<?php
$this->breadcrumbs = array(
	'Contact' => array('contact', 'id' => $id),
	'Manage',
);

?>
<?php
$this->menu = array(
	array('label' => 'Manage Customer', 'url' => array('admin')),
	array('label' => 'Add Contact', 'url' => array('createContact', 'id' => $id)),
);
$flashMessages = Yii::app()->user->getFlashes();
if ($flashMessages)
{
	foreach ($flashMessages as $key => $message)
	{
		echo '<div class="flash-' . $key . '">' . $message . "</div>";
	}
}
?>

<h4 class="ui-box-header ui-corner-all">
	Manage Contacts for <?php echo $name ?>
</h4>
<br />
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'contact-grid',
	'dataProvider'=>$customer_contacts,
	'ajaxUpdate'=>true,
	'htmlOptions'=>array('class'=>'hastable'),	
	'columns'=>array(
		array(
			'name'=>'info.full_name',
			'header'=>'Full Name',
		),
		array(
			'name'=>'info.phone1',
			'header'=>'Phone Number',
		),
		array(
			'name'=>'info.email',
			'header'=>'Email',
		),
		array(
			'class'=>'CButtonColumn',
			'deleteButtonUrl'=>'Yii::app()->createUrl("/customer/deleteContact", array("id" => $data->info->id))',
			'updateButtonUrl'=>'Yii::app()->createUrl("/customer/updateContact", array("id" => $data->info->id))',
			'buttons' =>array (
				'view'=>array(
					'visible' => 'false',
				),
			),
		),
	),
)); ?>