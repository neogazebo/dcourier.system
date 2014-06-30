<div class="form">

	<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'invoices-form',
		'enableAjaxValidation' => false,
			));

	?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php if ($model->getScenario() == 'insert'): ?>
		<?php echo $form->errorSummary($formInvoice); ?>

		<div class="row">
			<?php echo $form->labelEx($formInvoice, 'method'); ?>
			<div id="status-radiobutton">
				<?php echo $form->radioButtonList($formInvoice, 'method', $formInvoice->listcustom); ?>
				<?php echo $form->error($formInvoice, 'method'); ?>
			</div>
		</div>

		<div id="imthd">
			<div class="row" style="display: none" id="trans-not_all">
				<?php echo $form->labelEx($formInvoice, 'trans_month'); ?>
				<?php echo $form->dropDownList($formInvoice, 'trans_month', $formInvoice->listMonth(), array('prompt' => 'select month')); ?>
				<?php echo $form->error($formInvoice, 'trans_month'); ?>
			</div>

			<div class="row" style="display: none" id="trans-custom">
				<?php
				$this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider' => $cust_transaction,
					'htmlOptions' => array('class' => 'hastable'),
					'columns' => array(
						array(
							'header' => 'Shipper',
							'type' => 'raw',
							'value' => function ($data, $row)
							{
								return $data->getShipmentData()->shipper_name;
							},
						),
						array(
							'header' => 'Consignee',
							'type' => 'raw',
							'value' => function($data, $row)
							{
								return $data->getShipmentData()->receiver_name . '<br />' . $data->getShipmentData()->receiver_address;
							},
						),
						array(
							'header' => 'Ship Date',
							'type' => 'raw',
							'value' => function($data, $row)
							{
								return Yii::app()->dateFormatter->formatDateTime($data->getShipmentData()->delivery_date, 'medium', null);
							}
						),
						array(
							'header' => 'Orig',
							'type' => 'raw',
							'value' => function($data, $row)
							{
								switch ($data->getShipmentData()->service_type)
								{
									case ('domestic' || 'city') :
										return $data->getShipmentData()->shipper_city;
										break;
									case 'international':
										return $data->getShipmentData()->shipper_country;
										break;
								}
							}
						),
						array(
							'header' => 'Dest',
							'type' => 'raw',
							'value' => function($data, $row)
							{
								switch ($data->getShipmentData()->service_type)
								{
									case ('domestic' || 'city') :
										return $data->getShipmentData()->receiver_city;
										break;
									case 'international':
										return $data->getShipmentData()->reciver_country;
										break;
								}
							}
						),
						array(
							'header' => 'Price',
							'type' => 'raw',
							'value' => function($data, $row)
							{
								$print = '';
								$charges = $data->getShipmentCharges();

								foreach ($charges as $charge)
								{
									$print .= 'Rp' . number_format($charge['cost'], 2, ',', '.') . '<br />';
								}
								return $print;
							}
						),
						array(
							'header' => 'Name',
							'type' => 'raw',
							'footer' => 'Total Ammount',
							'value' => function($data, $row)
							{
								$charges = $data->getShipmentCharges();
								$print = '';
								foreach ($charges as $charge)
								{
									$print .= $charge['name'] . '<br />';
								}
								return $print;
							}
						),
						array(
							'name' => '',
							'type' => 'raw',
							'value' => function($data, $row)
							{
								return CHtml::CheckBox('InvoiceForm[trans_id][]', false, array('value' => $data->id));
							}
						)
					),
				));

				?>
			</div>
		</div>
		<?php echo $form->hiddenField($formInvoice, 'cid', array('value' => $customer->id)); ?>

		<?php
	elseif ($model->getScenario() == 'update'):

		?>
		<div class="row">
			<?php echo $form->labelEx($model, 'tgl_pembayaran'); ?>
			<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'attribute' => 'tgl_pembayaran',
				'model' => $model,
				'options' => array(
					'yearRange' => '-0:+7',
					'changeYear' => 'true',
					'changeMonth' => 'true',
				),
			));

			?>
			<?php echo $form->error($model, 'tgl_pembayaran'); ?>
		</div>
<?php endif; ?>
	<div class="row buttons">
	<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');
$script = "$('#status-radiobutton input').live('click',function(){ $('#imthd > div').slideUp();$('#imthd #trans-'+$(this).attr('value')).show(); });if($('#InvoiceForm_method_1').attr('checked') == 'checked' ) { $('#trans-not_all').show(); }else if($('#InvoiceForm_method_2').attr('checked') == 'checked' ){ $('#trans-custom').show(); };";
$cs->registerScript('show-hide-invoicemethod', $script, CClientScript::POS_READY);

?>