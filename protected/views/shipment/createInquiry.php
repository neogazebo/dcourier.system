<?php
$this->breadcrumbs = array(
	'Shipments' => array('admin'),
	'Create',
);

$this->menu = array(
	array('label' => 'Manage Shipment', 'url' => array('admin')),
);

?>

<h4 class="ui-box-header ui-corner-all">
	Inquiry
</h4>

<div class="form">
<?php
$form = $this->beginWidget('CActiveForm', array(
	'id' => 'shipment-form',
	'enableAjaxValidation' => false,
	'enableClientValidation' => true,
	'clientOptions' => array(
         'validateOnSubmit'=>true,
         'validateOnChange'=>false,
         'afterValidate'=>'js:valideteFancy'
     )
	));

if ($form instanceof CActiveForm)
	;

$data_render = array(
	'inquiry' => $inquiry,
	'customer' => $customer,
	'goods_type' => CHtml::listData($good_types, 'code', 'desc'),
	'form' => $form,
	'items' => $items,
	'costs' => $costs,
	'contact' => $contact,
	'shipment' => $shipment,
);
$shipment->setScenario('cek-rate');
?>
	<div class="row">
		<?php echo $form->labelEx($inquiry, 'customer_account') ?>
		<?php echo $form->textfield($inquiry, 'customer_account',array('value' => $customer->accountnr)) ?>
		<?php echo $form->hiddenField($shipment, 'customer_id', array('value' => $customer->id)) ?>
	</div>
	<?php echo $form->hiddenField($inquiry,'data_rate') ?>
	
	<div id="from-to">
		<?php echo $this->renderPartial('_form', $data_render);?>
	</div>

	<div id="inquiry-detail">
		<a name="inquiry-detail"></a>
		<?php echo $this->renderPartial('_inquiryDetails', $data_render);?>
	</div>
	
	<div id="ship-rate">
		<a name="ship-rate"></a>
		<?php echo $this->renderPartial('_formRate', $data_render);?>
	</div>
	
	<div id="ship-detail">
		<a name="ship-detail"></a>
		<?php echo $this->renderPartial('_shipmentDetail', $data_render);?>
	</div>
	
	<div id="pickup-detail">
		<a name="pickup-detail"></a>
		<?php echo $this->renderPartial('_formPickup', $data_render);?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Save Order') ?>
	</div>
	
	<?php echo CHtml::hiddenField('visited','',array('id' => 'visited')) ?>
<?php $this->endWidget(); ?>
	
</div><!-- form -->
<?php 
$cs = Yii::app()->clientScript;
//$cs->registerScript('first_in',"$(document).ready(function(){ $('.form #ship-detail,.form #ship-rate').hide() }) ",CClientScript::POS_HEAD);
//$cs->registerScript('cek_refresh',"$(window).bind('beforeunload', function(){ return '' });",CClientScript::POS_HEAD);
?>