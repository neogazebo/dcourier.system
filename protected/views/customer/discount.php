<?php
$this->breadcrumbs = array(
	'Customers' => array('admin'),
);

$this->menu = array(
	array('label' => 'List Customer', 'url' => array('admin')),
	array('label' => 'Manage Customer', 'url' => array('update', 'id' => $customer->id)),
);

?>

<h4 class="ui-box-header ui-corner-all">
	Discount List for customer <?php echo $customer->name ?>
</h4>
<br />
<?php
$form = $this->beginWidget('CActiveForm', array(
	'id' => 'customer-discount--form',
	'enableAjaxValidation' => false,
	'enableClientValidation' => true,
	'clientOptions' => array('validateOnSubmit' => true)
		));

?>
<div class="grid">
	<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'discount-grid',
		'filter' => $services,
		'dataProvider' => $services->search(),
		'htmlOptions' => array('class' => 'hastable'),
		'columns' => array(
			array(
				'header' => 'Product Service Code',
				'type' => 'raw',
				'value' => '$data->productService->code',
				'name'=> 'search_code'
			),
			array(
				'header' => 'Carrier',
				'type' => 'raw',
				'value' => '$data->rateCompanyService->rateCompany->name'
			),
			array(
				'header' => 'Company Service Name',
				'type' => 'raw',
				'value' => '$data->rateCompanyService->name'
			),
			array(
				'header' => 'Vendor Discount (%)',
				'type' => 'raw',
				'value' => function($data, $row) use ($customer)
				{
					return CustomerDiscount::model()->getTextField($customer->id, $data->id, $row, 'vendor_discount',$data->rateCompanyService->rateCompany->id,TRUE,3);
				}
			),
			array(
				'header' => 'discount invoice (%)',
				'type' => 'raw',
				'value' => function($data, $row) use ($customer)
				{
					return CustomerDiscount::model()->getDiscountTextField($customer->id, $data->id, $row);
				}
			),
			array(
				'header' => 'Harga invoice',
				'type' => 'raw',
				'value' => function($data, $row) use ($customer)
				{
					return CustomerDiscount::model()->getTextField($customer->id, $data->id, $row, 'harga_invoice',$data->rateCompanyService->rateCompany->id);
				}
			),
			array(
				'header' => 'discount api (%)',
				'type' => 'raw',
				'value' => function($data, $row) use ($customer)
				{
					return CustomerDiscount::model()->getTextField($customer->id, $data->id, $row, 'discount_api',$data->rateCompanyService->rateCompany->id,TRUE,3);
				}
			),
			array(
				'header' => 'Harga api',
				'type' => 'raw',
				'value' => function($data, $row) use ($customer)
				{
					return CustomerDiscount::model()->getTextField($customer->id, $data->id, $row, 'harga_api',$data->rateCompanyService->rateCompany->id);
				}
			),
			array(
				'header' => 'Use rate',
				'class'=>'CFCheckBoxColumn',
				'selectableRows'=>2,
				'customer_id' =>  $customer->id,
				'checkBoxHtmlOptions' => array('name' => 'CustomerDiscount'),
				'forAttribute' => 'use_rate',
			),
			array(
				'header' => 'Show in API',
				'class'=>'CFCheckBoxColumn',
				'selectableRows'=>2,
				'customer_id' =>  $customer->id,
				'checkBoxHtmlOptions' => array('name' => 'CustomerDiscount'),
				'forAttribute' => 'show_in_api',
			),
		),
	));

	?>
</div>
<?php echo CHtml::submitButton('Save'); ?>

<?php $this->endWidget(); ?>