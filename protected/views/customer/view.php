<?php
$this->breadcrumbs = array(
	'Customers' => array('admin'),
	$model->name,
);

$this->menu = array(
	array('label' => 'Update Customer', 'url' => array('update', 'id' => $model->id)),
	array('label' => 'Delete Customer', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
	array('label' => 'Manage Invoice', 'url' => array('invoices/viewInvoice', 'id' => $model->id))
);
if(!($model->accountnr))
	array_push($this->menu, array('label' => 'Generate Account Number', 'url' => array('generateAcountNumber', 'id' => $model->id)))

?>

<h4 class="ui-box-header ui-corner-all"><?php echo ucfirst($model->name); ?></h4>
<br />
<div class="search-form">
	<?php
	$this->renderPartial('_search', array(
		'model' => $model,
	));

	?>
</div><!-- search-form -->
<?php
$this->widget('zii.widgets.CDetailView', array(
	'data' => $model,
	'attributes' => array(
		'accountnr',
		'numberID',
		'type',
		'billing_cycle',
		array(
			'label' => 'created',
			'type' => 'raw',
			'value' => $model->getContactData()->created
		),
		'name',
		array(
			'label' => 'Full Name',
			'type' => 'raw',
			'value' => $model->getContactData()->full_name
		),
		array(
			'label' => 'Birth Place',
			'type' => 'raw',
			'value' => $model->getContactData()->birth_place
		),
		array(
			'label' => 'Date of Birth',
			'type' => 'raw',
			'value' => Yii::app()->dateFormatter->formatDateTime($model->getContactData()->dob, 'medium', null)
		),
		array(
			'label' => 'Phone',
			'type' => 'raw',
			'value' => $model->getContactData()->phone1
		),
		array(
			'label' => 'Phone#2',
			'type' => 'raw',
			'value' => $model->getContactData()->phone2
		),
		array(
			'label' => 'Fax',
			'type' => 'raw',
			'value' => $model->getContactData()->fax
		),
		array(
			'label' => 'Phone',
			'type' => 'raw',
			'value' => $model->getContactData()->email
		),
		array(
			'label' => 'Address',
			'type' => 'raw',
			'value' => $model->getContactData()->address
		),
		array(
			'label' => 'Province',
			'type' => 'raw',
			'value' => $model->getContactData()->province
		),
		array(
			'label' => 'Country',
			'type' => 'raw',
			'value' => $model->getContactData()->country
		),
		array(
			'label' => 'City',
			'type' => 'raw',
			'value' => $model->getContactData()->city
		),
		array(
			'label' => 'Postal',
			'type' => 'raw',
			'value' => $model->getContactData()->postal
		),
		array(
			'label' => 'Facebook',
			'type' => 'raw',
			'value' => $model->getContactData()->facebook
		),
		array(
			'label' => 'Twitter',
			'type' => 'raw',
			'value' => $model->getContactData()->twitter
		),
		array(
			'label' => 'Occupation',
			'type' => 'raw',
			'value' => $model->getContactData()->jabatan
		),
		array(
			'label' => 'Allow API',
			'type' => 'raw',
			'value' => $model->is_allow_api()
		),
		array(
			'label' => 'Sales Territory / Name',
			'type' => 'raw',
			'value' => $model->getSalesTerritory()
		)
	),
));

?>

<br/>
<?php echo CHtml::link('Inquiry', Yii::app()->createUrl('shipment/cekRate', array('customer_id' => $model->id)), array('class' => 'l-btn')) ?>
<br /><br />

<h1>Contact Details</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'contact-grid',
	'dataProvider' => $contact_list,
	'ajaxUpdate' => true,
	'htmlOptions' => array('class' => 'hastable'),
	'columns' => array(
		array(
			'name' => 'info.full_name',
			'header' => 'Full Name',
		),
		array(
			'name' => 'info.phone1',
			'header' => 'Phone Number',
		),
		array(
			'name' => 'info.email',
			'header' => 'Email',
		),
		array(
			'name' => 'info.address',
			'header' => 'Address',
		)
	),
));

?>
<br />

<h1>Shipment Logs</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'shipment-log-grid',
	'dataProvider' => $shipment_log,
	'ajaxUpdate' => false,
	'htmlOptions' => array('class' => 'hastable'),
	'columns' => array(
		array(
			'name' => 'created',
			'header' => 'Date',
			'value' => function($data, $row)
			{
				return Yii::app()->dateFormatter->formatDateTime($data->created, 'medium', null);
			}
		),
		array(
			'name' => 'awb',
			'type' => 'raw',
			'value' => function($data, $row)
			{
				return CHtml::link($data->awb, array('ordertracking/TrackingDetails', 'id' => $data->id));
			}
		),
		array(
			'header' => 'Last Status',
			'name' => 'shipping_status',
			'type' => 'raw',
			'value' => function($data, $row)
			{
				return $data->shippingStatus->status;
			}
		),
		'type',
		'service_type',
		array(
			'header' => 'Origins',
			'value' => function($data, $row)
			{
				if ($data->shipper_country == 'Indonesia')
					return $data->shipper_city;
				else
					return $data->shipper_country;
			}
		),
		array(
			'header' => 'Destinations',
			'value' => function($data, $row)
			{
				if ($data->receiver_country == 'Indonesia')
					return $data->receiver_city;
				else
					return $data->receiver_country;
			}
		),
	),
));

?>

<br /><br />
<div id="cust_comment">
	<?php foreach ($comments as $comment): ?>
		<?php
		$this->renderPartial('_comment', array(
			'comment' => $comment,
		));

		?>
	<?php endforeach; ?>
</div>

<div class="row">
	<?php echo CHtml::activeTextArea($new_comment, 'comment', array('cols' => 126, 'rows' => 5)) ?>
</div>
<div class="row">
<?php
echo CHtml::ajaxButton('Comment', array('customer/createComments', 'id' => $model->id), array(
	'type' => 'post',
	'data' => 'js:$("textarea#CustomerComment_comment").serialize()',
	'dataType' => 'html',
	'success' => 'js:function(data){
						$("#cust_comment").append(data);
						$("textarea#CustomerComment_comment").val("");
						return true;
			}',
))

?>
</div>
