<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'customer-shipping-profile',
		'enableAjaxValidation' => false,
		'enableClientValidation' => false
			));
?>

<div class="form">
		<div class="row">
		<?php echo $form->label($customer_shipping_profile,'product_id'); ?>
		<?php echo $form->dropDownList($customer_shipping_profile,'product_id', CHtml::listData(Product::model()->findAll(), 'id', 'name'),array('prompt' => '')); ?>
		<?php echo $form->error($customer_shipping_profile,'product_id'); ?>
	</div>

	<div class="row" id="prid">
		<?php echo $form->label($customer_shipping_profile,'product_service_id'); ?>
		<?php echo $form->dropDownList($customer_shipping_profile,'product_service_id', CHtml::listData(ProductService::model()->findAllByAttributes(array('product_id' => $product_id)), 'id', 'name'),array('prompt' => '')); ?>
		<?php echo $form->error($customer_shipping_profile,'product_service_id'); ?>
	</div>

	<div class="row" style="float: left;margin-right: 30px">
		<?php echo $form->label($customer_shipping_profile,'origin'); ?>
		<?php echo $form->textField($customer_shipping_profile,'origin',array('size' => 3)); ?>
		<?php echo $form->error($customer_shipping_profile,'origin'); ?>
	</div>

	<div class="row" style="float: left">
		<?php echo $form->label($customer_shipping_profile,'destination'); ?>
		<?php echo $form->textField($customer_shipping_profile,'destination',array('size' => 3)); ?>
		<?php echo $form->error($customer_shipping_profile,'destination'); ?>
	</div>
	<div class="clearfix"></div>
	
	<div class="row">
		<?php echo $form->label($customer_shipping_profile,'volume'); ?>
		<?php echo $form->textField($customer_shipping_profile,'volume'); ?>
		<?php echo $form->error($customer_shipping_profile,'volume'); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::submitButton($customer_shipping_profile->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
</div>

	<?php $this->endWidget(); ?>
<?php 
	$getProductServiceList = CHtml::ajax(array(
		'dataType' => 'html',
		'type' => 'post',
		'url' => array('customer/getProductServiceList'),
		'data' => 'js: {product_id:$(this).val()} ',
		'replace' => '#prid'
	));
	
$product_change = <<<EOD
	$('#CustomerShippingProfile_product_id').live('change',function(){ $getProductServiceList });
EOD;
	$cs = Yii::app()->clientScript;
	$cs->registerScript('getListProduct',$product_change);
?>