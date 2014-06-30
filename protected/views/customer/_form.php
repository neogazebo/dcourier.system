<div id="tabs">
	<ul>
		<li><a href="#tabs-1">General</a></li>
		<li><a href="#tabs-4">Detail</a></li>
		<?php if ($model->getScenario() == 'update'): ?>
			<li><a href="#tabs-2">Contact Information</a></li>
		<?php endif; ?>
		<li><a href="#tabs-3">Notification</a></li>
		<li><a href="#tabs-5">Billing</a></li>
		<li><a href="#tabs-6">Map</a></li>
		<li><a href="#tabs-7">Comments</a></li>
		<?php if ($model->getScenario() == 'update'): ?>
			<li><a href="#tabs-10">Shipping Profile</a></li>
			<li><a href="#tabs-8">API</a></li>
			<li><a href="#tabs-9">Web Access</a></li>
		<?php endif; ?>
	</ul>
	<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'customer-form',
		'enableAjaxValidation' => false,
		'enableClientValidation' => true,
		'clientOptions' => array('validateOnSubmit' => true)
			));

	?>
	<div class="form">

		<div id="tabs-1">
			<div>
				<?php echo $form->labelEx($model, 'type'); ?>
				<?php echo $form->dropDownList($model, 'type', $model->listType()) ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model, 'name'); ?>
				<?php echo $form->textField($model, 'name', array('size' => 30, 'maxlength' => 80)); ?>
				<?php echo $form->error($model, 'name'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($contact, 'phone1'); ?>
				<?php echo $form->textField($contact, 'phone1', array('size' => 30, 'maxlength' => 80)); ?>
				<?php echo $form->error($contact, 'phone1'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($contact, 'email'); ?>
				<?php echo $form->textField($contact, 'email', array('size' => 30, 'maxlength' => 80)); ?>
				<?php echo $form->error($contact, 'email'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($contact, 'full_name'); ?>
				<?php echo $form->textField($contact, 'full_name', array('size' => 30, 'maxlength' => 80)); ?>
				<?php echo $form->error($contact, 'full_name'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model, 'accountnr'); ?>
				<?php echo $form->textField($model, 'accountnr', array('size' => 30, 'maxlength' => 80)); ?>
				<?php echo $form->error($model, 'accountnr'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model, 'sales'); ?>
				<?php echo $form->dropDownList($model, 'sales', CHtml::listData(SalesTerritory::model()->findAll(), 'user_id', 'territory'), array('prompt' => '')); ?>
				<?php echo $form->error($model, 'accountnr'); ?>
			</div>
		</div>
		<div id="tabs-4">
			<?php
			/**
			 * creating ajax function untuk mencari daftar city berdasarkan provinceId
			 * return array dari city
			 *  
			 */
			$sugggestCity = CHtml::ajax(array(
						'url' => array('shipment/suggestCity'),
						'dataType' => 'json',
						'data' => array(
							'term' => 'js:request.term',
							'pid' => 'js:$("input#province_id").val()'
						),
						'success' => 'js:function(data){
														realData=$.makeArray(data);
															response($.map(realData, function (item){return{
																	did:item.did,
																	zid:item.zid,
																	value:item.value,
																	label:item.label
																}
															}))
													}'
							)
			);

			?>
			<div class="row">
				<?php echo $form->labelEx($contact, 'jabatan'); ?>
				<?php echo $form->textField($contact, 'jabatan'); ?>
				<?php echo $form->error($contact, 'jabatan'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($contact, 'birth_place'); ?>
				<?php echo $form->textField($contact, 'birth_place', array('size' => 30, 'maxlength' => 80)); ?>
				<?php echo $form->error($contact, 'birth_place'); ?>
			</div>
			<div class="row">
				<?php echo $form->labelEx($contact, 'dob'); ?>
				<?php
				$this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'name' => 'Contact[dob]',
					'attribute' => 'dob',
					'model' => $contact,
					'options' => array(
						'yearRange' => '-80:-7',
						'changeYear' => 'true',
						'changeMonth' => 'true',
					),
				));

				?>
				<?php echo $form->error($contact, 'dob'); ?>
			</div>

			<div class="row">
				<?php echo $form->label($contact, 'country') ?>
				<?php
				$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					'model' => $contact,
					'attribute' => 'country',
					'sourceUrl' => array('shipment/suggestInternationalZoneCountry'),
					'options' => array(
						'select' => 'js:function(event,ui){
								return true;
							}',
					)
						)
				);

				?>
			</div>

			<div class="row at_comp">
				<?php echo $form->labelEx($contact, 'province') ?>
				<?php
				$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					'model' => $contact,
					'attribute' => 'province',
					'sourceUrl' => array('shipment/suggestProvince'),
					'options' => array(
						'select' => 'js:function(event,ui){
								$("#province_id").val(ui.item.id);
								return true;
							}',
					)
						)
				);

				?>
				<?php echo $form->error($contact, 'province'); ?>
				<?php echo CHtml::hiddenField('province_id') ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($contact, 'address'); ?>
				<?php echo $form->textArea($contact, 'address', array('rows' => 2, 'cols' => 25)); ?>
				<?php echo $form->error($contact, 'address'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($contact, 'city') ?>
				<?php
				$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					'model' => $contact,
					'attribute' => 'city',
					'source' => "js:function(request,response){{$sugggestCity}}",
					'options' => array(
						'select' => 'js:function(event,ui){
								return true;
							}',
					)
						)
				);

				?>
				<?php echo $form->error($contact, 'city'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($contact, 'postal'); ?>
				<?php echo $form->textField($contact, 'postal'); ?>
				<?php echo $form->error($contact, 'postal'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($contact, 'phone2'); ?>
				<?php echo $form->textField($contact, 'phone2', array('size' => 30, 'maxlength' => 80)); ?>
				<?php echo $form->error($contact, 'phone2'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($contact, 'fax'); ?>
				<?php echo $form->textField($contact, 'fax', array('size' => 30, 'maxlength' => 80)); ?>
				<?php echo $form->error($contact, 'fax'); ?>
			</div>
		</div>
		<?php if ($model->getScenario() == 'update'): ?> 
			<div id="tabs-2">
				<?php
				$this->widget('zii.widgets.grid.CGridView', array(
					'id' => 'rekening-grid',
					'dataProvider' => $customer_contacs,
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
							'class' => 'CButtonColumn',
							'deleteButtonUrl' => 'Yii::app()->createUrl("/customer/deleteContact", array("id" => $data->info->id))',
							'updateButtonUrl' => 'Yii::app()->createUrl("/customer/updateContact", array("id" => $data->info->id))',
							'buttons' => array(
								'view' => array(
									'visible' => 'false',
								),
							),
						),
					),
				));

				?>
				<?php echo CHtml::link('add contact', Yii::app()->createUrl('/customer/createContact', array('id' => $model->id))) ?>
			</div>
		<?php endif; ?>
		<div id="tabs-3">
			<div class="row">
				<div id="status-radiobutton">
					<?php echo $form->labelEx($model, 'notification_sms'); ?>
					<?php echo $form->checkBox($model, 'notification_sms') ?>
				</div>
				<?php echo $form->error($model, 'notification_sms'); ?>
			</div>

			<div class="row">
				<div id="status-radiobutton">
					<?php echo $form->labelEx($model, 'notification_email'); ?>
					<?php echo $form->checkBox($model, 'notification_email') ?>
				</div>
				<?php echo $form->error($model, 'notification_email'); ?>
			</div>
		</div>
		<div id="tabs-5">
			<div class="row">
				<?php echo $form->labelEx($model, 'billing_cycle'); ?>
				<?php echo $form->textField($model, 'billing_cycle', array('size' => 3, 'maxlength' => 5)); ?>	days
				<?php echo $form->error($model, 'billing_cycle'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model, 'numberID'); ?>
				<?php echo $form->textField($model, 'numberID'); ?>
				<?php echo $form->error($model, 'numberID'); ?>
			</div>

			<?php if ($model->getScenario() == 'update'): ?>
				<?php
				$this->widget('zii.widgets.grid.CGridView', array(
					'id' => 'contact-grid',
					'dataProvider' => $rekening,
					'ajaxUpdate' => true,
					'htmlOptions' => array('class' => 'hastable'),
					'columns' => array(
						'bank',
						'cabang',
						'rekening',
						'nama',
						array(
							'class' => 'CButtonColumn',
							'deleteButtonUrl' => 'Yii::app()->createUrl("/customer/deleteRekening", array("id" => $data->id))',
							'updateButtonUrl' => 'Yii::app()->createUrl("/customer/updateRekening", array("id" => $data->id))',
							'buttons' => array(
								'view' => array(
									'visible' => 'false',
								),
							),
						),
					),
				));

				?>
				<div class="link_btn">
					<?php echo CHtml::link('add rekening', Yii::app()->createUrl('/customer/createRekening', array('id' => $model->id))) ?>
					<br />
					<?php echo CHtml::link('discount management', Yii::app()->createUrl('/customer/discount', array('id' => $model->id))) ?>
				</div>
			<?php endif; ?>

		</div>
		<div id="tabs-6">
			<div id="mapCanvas" style="width: 500px; height: 300px;"></div>
			<?php
			echo $form->textField($contact, 'gmap_lat');
			echo $form->textField($contact, 'gmap_long');

			?>
		</div>
		<div id="tabs-7">
			<div class="row">
				<?php echo $form->labelEx($model, 'comments'); ?>
				<?php echo $form->textArea($model, 'comments', array('rows' => 6, 'cols' => 30)); ?>
				<?php echo $form->error($model, 'comments'); ?>
			</div>
		</div>
		<?php if ($model->getScenario() == 'update'): ?>
			<div id="tabs-10">
				<?php
				$this->renderPartial('_shipping_profile', array('customer_shipping_profile' => $customer_shipping_profile));
				echo CHtml::link('Create New Shipping Profile', array('customer/createShippingProfile', 'cid' => $model->id));

				?>
			</div>

			<div id="tabs-8">
				<div class="row">
					<?php echo $form->labelEx($model, 'is_allow_api'); ?>
					<?php echo $form->checkbox($model, 'is_allow_api') ?>
				<?php echo $form->error($model, 'is_allow_api'); ?>
				</div>
				<?php
				echo CHtml::ajaxButton('generate token', array('customer/createToken', 'cust_id' => $model->id), array(
					'type' => 'get',
					'dataType' => 'html',
					'replace' => '#cust-token',
					'data' => ''
				));

				?>
				<?php
				$this->renderPartial('_tokens', array('customer_tokens' => $customer_tokens));

				?>
				<div class="link_btn">
					<br/>
	<?php echo CHtml::link('Add Api Access', Yii::app()->createUrl('/apiAccesslist/admin', array('customer_id' => $model->id))) ?>
				</div>
			</div>
			<div id="tabs-9">
				<div class="row">
					<?php echo $form->labelEx($model, 'username') ?>
	<?php echo $form->textField($model, 'username') ?>
					<?php echo $form->error($model, 'username') ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model, 'new_password') ?>
	<?php echo $form->passwordField($model, 'new_password') ?>
					<?php echo $form->error($model, 'new_password') ?>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model, 'new_password_confirmation') ?>
	<?php echo $form->passwordField($model, 'new_password_confirmation') ?>
			<?php echo $form->error($model, 'new_password_confirmation') ?>
				</div>
			</div>
			<?php endif; ?>
		<div class="row tab-out">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
		</div>
<?php $this->endWidget(); ?>
	</div>
</div>
<?php
$gmp = Yii::app()->clientScript;
$gmp->registerCoreScript('jquery');
$gmp->registerScriptFile('http://maps.google.com/maps/api/js?sensor=false', CClientScript::POS_END);

?>
<script type="text/javascript">
	$(document).ready(function($){
		var lat = -6.15014; 
		var lng= 106.728; 
		var marker;
                                                
		geocoder = new google.maps.Geocoder();
		var latLng = new google.maps.LatLng(lat, lng);
          
		var map = new google.maps.Map(document.getElementById('mapCanvas'), {
			zoom: 8,
			center: latLng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
          
		var marker = new google.maps.Marker({
			position: latLng,
			title: 'Location',
			map: map,
			draggable: true
		});

		google.maps.event.addListener(marker, 'dragend', function() {
			var pos = marker.getPosition();
			$('#Contact_gmap_lat').val(pos.lat());
			$('#Contact_gmap_long').val(pos.lng());
		});
                                        
		$('.row').children().blur(function(){
			var address = $('#Contact_address').val()+','+$('#Contact_city').val();
			geocoder.geocode( { 'address': address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					map.setCenter(results[0].geometry.location);
					marker.setPosition(results[0].geometry.location);
					var geoloc = results[0].geometry.location;
					geoloc = geoloc.toString().replace("(","");
					geoloc = geoloc.toString().replace(")","");
					geoloc = geoloc.toString().split(",");
					var lat = $.trim(geoloc[0]);
					var lon = $.trim(geoloc[1]);
					$('#Contact_gmap_lat').val(lat);
					$('#Contact_gmap_long').val(lon); 
				}
			});
		});
		
		$('#tabs').tabs();
	}
);
</script>