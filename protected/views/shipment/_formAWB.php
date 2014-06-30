<div id="formAWB_container">
	<style type="text/css">
		label.custFont
		{
			font-style: italic;
			color:#ff0000;
		}
		label.description{
			color: #ff0000;
		}
	</style>
	<script type="text/javascript">
		Ext.require([
			'Ext.form.*',
			'Ext.layout.container.Column',
			'Ext.window.MessageBox',
			'Ext.fx.target.Element',
			'Ext.tab.*',
			'Ext.window.*',
			'Ext.tip.*',
			'Ext.layout.container.Border',
			'Ext.grid.*',
			'Ext.data.*',
			'Ext.util.*',
			'Ext.state.*',
			'Ext.panel.*',
		]);
		
		Ext.define('mSurcharges', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'surcharge'},
				{name: 'rate'}
			]
		});
		Ext.onReady(function(){
			// Start the mask on the body and get a reference to the mask
			var splashscreen = Ext.getBody().mask('Loading application', 'splashscreen');
			
			var task = new Ext.util.DelayedTask(function() {
				// Fade out the body mask
				splashscreen.fadeOut({
					duration: 1000,
					remove:true
				});
				// Fade out the icon and message
				splashscreen.next().fadeOut({
					duration: 1000,
					remove:true,
					listeners: {
						afteranimate: function() {
							// Set the body as unmasked after the animation
							Ext.getBody().unmask();
						}
					}
				});
			});
			// Run the fade 500 milliseconds after launch.
			task.delay(500);
			
			/**
			 * function to get destination routing code
			 */
			var getRoutingCode = function(country,postal,dest,url){
				if(!(!country) && !(!postal))
				{
					Ext.Ajax.request({
						method:'POST',
						url: url,
						params: {country : country,postal:postal},
						success: function(result, request){
							var res = Ext.decode(result.responseText);
							if(res.status == 'success')
							{
								formPanel.down('#'+res.service_type).setValue(true);
								if(res.data.length == 1)
									dest.setValue(res.data[0].code);
								else if(res.data.length > 1)
								{
									var sStore = Ext.data.StoreManager.lookup('routingStore');
									sStore.loadRawData(res.data);
									var win_routing = Ext.create('widget.window',{
										title: 'Available Routing',
										closable: true,
										modal:true,
										closeAction: 'hide',
										layout: {
											type: 'auto'
										},
										items:[
											{
												xtype:'grid',
												listeners:{
													itemdblclick:function(grid, record) {
														dest.setValue(record.get('code'));
														win_routing.hide();
													}
												},
												store :Ext.data.StoreManager.lookup('routingStore'),
												columns:
													[{text:'Routing',dataIndex:'code',width:130,align:'center'}]		
											}
										]
									}).show();
								}
							}
							else
								Ext.MessageBox.alert("There is no routing available");
						}
					});
				}
			}
			
			var setPrice = function(){
				var shipper_country = formPanel.down('#<?php echo CHtml::activeId($shipment, 'shipper_country') ?>').getValue();
				var receiver_country = formPanel.down('#<?php echo CHtml::activeId($shipment, 'receiver_country') ?>').getValue();
				var service_id = formPanel.down('#<?php echo CHtml::activeId($shipment, 'service_id') ?>').getValue();
				var val_product = formPanel.down('#<?php echo CHtml::activeId($shipment, 'service_type') ?>').getValue();
				for(var val in val_product)
					var product = val_product[val];
				var val_type = formPanel.down('#<?php echo CHtml::activeId($shipment, 'type') ?>').getValue();
				for(var val2 in val_type)
					var type = val_type[val2];
				var package_weight = formPanel.down('#<?php echo CHtml::activeId($shipment, 'package_weight') ?>').getValue();
				var receiver_postal = formPanel.down('#<?php echo CHtml::activeId($shipment, 'receiver_postal') ?>').getValue();
				var service_code = formPanel.down('#<?php echo CHtml::activeId($shipment, 'service_code') ?>').getValue();
				console.log(service_id);
				Ext.Ajax.request({
					method:'POST',
					url: '<?php echo $this->createUrl('shipment/getExtRate') ?>',
					params: function(){
						return "Shipment[service_id]="+service_id+"&Shipment[service_type]="+product+"&Shipment[package_weight]="+package_weight+"&Shipment[receiver_postal]="+receiver_postal+"&Shipment[service_code]="+service_code+"&Shipment[receiver_country]="+receiver_country+"&Shipment[shipper_country]="+shipper_country+"&Shipment[type]="+type;
					},
					success: function ( result, request ) {
						var res = Ext.decode(result.responseText);
						if(res.status == 'error')
							Ext.MessageBox.alert("No Service Available");
						else if(res.status == 'success')
						{
							formPanel.down('#<?php echo CHtml::activeId($shipment, 'shipping_charges') ?>').setValue(res.data);
						}
					}
				});
			};
			
			var carrierData = Ext.create('Ext.data.Store', {
				fields: ['id', 'code'],
				data : Ext.decode('<?php echo $shipment->GetExtCarrierData() ?>')
			});
			
			Ext.create('Ext.data.Store', {
				storeId:'serviceStore',
				fields:['service_id','service_name','code','carrier_service','vendor_name']
			});
			
			Ext.create('Ext.data.Store', {
				storeId:'routingStore',
				fields:['code']
			});
			
			var dataSurcharges = [
				['Add Weight', '5.000'], 
				['Add Km', '5.000'], 
				['Add Stop', '5.000'], 
				['Fragile', '25%'],
				['Shopping Fee', '5%'],
				['Cash On Delivery', '50%'],
				['Insurance', '3%'],
				['POD retrieval', '1.000'] 

			];
			
			var storeSurcharges = Ext.create('Ext.data.ArrayStore', {
				model: 'mSurcharges',
				data: dataSurcharges
			});
														
			var surchargesGrid = new Ext.grid.GridPanel({
				store:storeSurcharges,
				loadMask: true,
				columns:[
					{header : "Surcharge", dataIndex : 'surcharge', width:'70%'},
					{header : "Rate", dataIndex : 'rate', width:'30%'},
				],
				bbar: Ext.create('Ext.PagingToolbar', {
					pageSize:10,
					store: storeSurcharges,
					displayInfo: true,
					displayMsg: 'Displaying topics {0} - {1} of {2}',
					emptyMsg: "No topics to display"
				}),
				listeners : {
					itemdblclick : function(view, record, item, index, e, eOpts) {
						var label = record.get('surcharge');
						var value = record.get('rate');
						Ext.getCmp('insurance').setFieldLabel(label);
						Ext.getCmp('insurance').setValue(value);
						winSurcharge.hide();
					}
				}
			});
			var othersGrid = new Ext.grid.GridPanel({
				store:storeSurcharges,
				loadMask: true,
				columns:[
					{header : "Surcharge", dataIndex : 'surcharge', width:'70%'},
					{header : "Rate", dataIndex : 'rate', width:'30%'},
				],
				bbar: Ext.create('Ext.PagingToolbar', {
					pageSize:10,
					store: storeSurcharges,
					displayInfo: true,
					displayMsg: 'Displaying topics {0} - {1} of {2}',
					emptyMsg: "No topics to display"
				}),
				listeners : {
					itemdblclick : function(view, record, item, index, e, eOpts) {
						var label = record.get('surcharge');
						var value = record.get('rate');
						Ext.getCmp('other').setFieldLabel(label);
						Ext.getCmp('other').setValue(value);
						winOther.hide();
					}
				}
			});
			var winSurcharge = new Ext.Window
			({
				title	: 'Surcharges Charge',
				layout:'fit',
				width:400,
				height:300,
				closeAction:'hide',
				plain: true,
				items: [surchargesGrid]        
			});
			var winOther = new Ext.Window
			({
				title	: 'Others Charge',
				layout:'fit',
				width:400,
				height:300,
				closeAction:'hide',
				plain: true,
				items: [othersGrid]        
			});
			
			var formPanel = Ext.create('Ext.form.Panel', {
				width: 'auto',
				height: 665,
				frame:true,
				renderTo: 'formAWB_container',
				layout:'auto',
				buttonAlign: 'center',
				items: [
					{
						xtype:'panel',
						layout:{type:'vbox'},
						autoSize:'true',
						items :[
							{
								xtype:'hiddenfield',
								id:<?php echo CJSON::encode(CHtml::activeId($shipment, 'id')) ?>,
								name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'id')) ?>,
								value:<?php echo CJSON::encode($shipment->id) ?>
							},
							{
								xtype:'panel',
								layout:{type:'hbox'},
								width:'100%',
								height:150,
								border:false,
								items :[
									{
										xtype:'panel',
										flex:.35,
										height:150,
										border:false,
										items:[
											{
												xtype:'panel',
												height:140,
												width:'93%',
												title:'PAYER ACCOUNT NUMBER',
												layout:'absolute',
												items:[
													{
														xtype: 'radiogroup',
														fieldLabel: 'Payment Methode',
														labelSeparator:'',
														labelWidth:120,
														id:<?php echo CJSON::encode(CHtml::activeId($shipment, 'pay_by')) ?>,
														style:'margin: 2px 5px 2px 5px',
														items: <?php echo $shipment->getListPayByXtjs() ?>,
														y:5
													},
													{
														xtype: 'radiogroup',
														fieldLabel: 'Charge to',
														labelSeparator:'',
														id:<?php echo CJSON::encode(CHtml::activeId($shipment, 'payer')) ?>,
														labelWidth:120,
														style:'margin: 2px 5px 2px 5px',
														items: <?php echo $shipment->getListPayerXtjs() ?>,
														listeners: {
															change: function(field, newValue, oldValue) {
																var payers = this.getValue();
																var accountnr = formPanel.down('#<?php echo CHtml::activeId($customer, 'accountnr') ?>').getValue();
																var payer_name;
																var payer_address;
																var payer_phone;
																var payer_country;
																var payer_postal;
																var payer_name_reset;
																var payer_address_reset;
																var payer_phone_reset;
																var payer_country_reset;
																var payer_postal_reset;
																for(var val in payers)
																	var value = payers[val];
																if(!(!accountnr))
																{
																	Ext.Ajax.request({
																		method:'POST',
																		url: '<?php echo $this->createUrl('shipment/getExtCustomerData') ?>',
																		params: {accountnr : accountnr},
																		success: function(result, request) {
																			var res = Ext.decode(result.responseText);
																			if(res.status == 'success')
																			{
																				switch(value){
																					case 'shipper':
																						payer_name = '<?php echo CHtml::activeId($shipment, 'shipper_name') ?>';
																						payer_address = '<?php echo CHtml::activeId($shipment, 'shipper_address') ?>';
																						payer_phone = '<?php echo CHtml::activeId($shipment, 'shipper_phone') ?>';
																						payer_country = '<?php echo CHtml::activeId($shipment, 'shipper_country') ?>';
																						payer_postal = '<?php echo CHtml::activeId($shipment, 'shipper_postal') ?>';
																						payer_name_reset = '<?php echo CHtml::activeId($shipment, 'receiver_name') ?>';
																						payer_address_reset = '<?php echo CHtml::activeId($shipment, 'receiver_address') ?>';
																						payer_country_reset = '<?php echo CHtml::activeId($shipment, 'receiver_country') ?>';
																						payer_postal_reset = '<?php echo CHtml::activeId($shipment, 'receiver_postal') ?>';
																						payer_phone_reset = '<?php echo CHtml::activeId($shipment, 'receiver_phone') ?>';
																						break;
																					case 'consignee': 
																						payer_name_reset = '<?php echo CHtml::activeId($shipment, 'shipper_name') ?>';
																						payer_address_reset = '<?php echo CHtml::activeId($shipment, 'shipper_address') ?>';
																						payer_phone_reset = '<?php echo CHtml::activeId($shipment, 'shipper_phone') ?>';
																						payer_country_reset = '<?php echo CHtml::activeId($shipment, 'shipper_country') ?>';
																						payer_postal_reset = '<?php echo CHtml::activeId($shipment, 'shipper_postal') ?>';
																						payer_name = '<?php echo CHtml::activeId($shipment, 'receiver_name') ?>';
																						payer_address = '<?php echo CHtml::activeId($shipment, 'receiver_address') ?>';
																						payer_phone = '<?php echo CHtml::activeId($shipment, 'receiver_phone') ?>';
																						payer_country = '<?php echo CHtml::activeId($shipment, 'receiver_country') ?>';
																						payer_postal = '<?php echo CHtml::activeId($shipment, 'receiver_postal') ?>';
																						break;
																					case '3rdparty': 
																						break;
																					}
																					formPanel.down('#'+'<?php echo CHtml::activeId($shipment, 'customer_id') ?>').setValue(res.data.customer_id);
																					formPanel.down('#'+payer_name).setValue(res.data.name);
																					formPanel.down('#'+payer_name_reset).reset();
																					formPanel.down('#'+payer_address).setValue(res.data.address);
																					formPanel.down('#'+payer_address_reset).reset();
																					formPanel.down('#'+payer_phone).setValue(res.data.phone);
																					formPanel.down('#'+payer_phone_reset).reset();
																					formPanel.down('#'+payer_country).setValue(res.data.country);
																					formPanel.down('#'+payer_country_reset).reset();
																					formPanel.down('#'+payer_postal).setValue(res.data.postal);
																					formPanel.down('#'+payer_postal_reset).reset();
																				}
																				else
																					Ext.MessageBox.alert('Customer data not available');
																			}
																		});
																	}
																}
															},
															y:35
														},
														{
															xtype : 'hiddenfield',
															name : <?php echo CJSON::encode(CHtml::activeName($shipment, 'customer_id')) ?>,
															id:<?php echo CJSON::encode(CHtml::activeId($shipment, 'customer_id')) ?>
														},
														{
															xtype:'textfield',
															labelSeparator:'',
															labelWidth:120,
															value:<?php echo CJSON::encode($customer->accountnr) ?>,
															fieldLabel:'Payer Account No',
															itemId:<?php echo CJSON::encode(CHtml::activeId($customer, 'accountnr')) ?>,
															anchor:'95%',
															enableKeyEvents : true,
															name : <?php echo CJSON::encode(CHtml::activeName($customer, 'accountnr')) ?>,
															listeners: {
																keypress: function(txt, e) {
																	var accountnr = this.getValue();
																	var payers = formPanel.down('#<?php echo CHtml::activeId($shipment, 'payer') ?>').getValue();
																	var payer_name;
																	var payer_address;
																	var payer_phone;
																	var payer_country;
																	var payer_postal;
																	var payer_name_reset;
																	var payer_address_reset;
																	var payer_phone_reset;
																	var payer_country_reset;
																	var payer_postal_reset;
																	for(var val in payers)
																		var value = payers[val];
																	
																	if (e.keyCode == 13) {
																		Ext.Ajax.request({
																			method:'POST',
																			url: '<?php echo $this->createUrl('shipment/getExtCustomerData') ?>',
																			params: {
																				accountnr : accountnr
																			},
																			success: function ( result, request ) {
																				var res = Ext.decode(result.responseText);
																				if(res.status == 'success')
																				{
																					switch(value){
																						case ('shipper' || ''):
																							payer_name = '<?php echo CHtml::activeId($shipment, 'shipper_name') ?>';
																							payer_address = '<?php echo CHtml::activeId($shipment, 'shipper_address') ?>';
																							payer_phone = '<?php echo CHtml::activeId($shipment, 'shipper_phone') ?>';
																							payer_country = '<?php echo CHtml::activeId($shipment, 'shipper_country') ?>';
																							payer_postal = '<?php echo CHtml::activeId($shipment, 'shipper_postal') ?>';
																							payer_name_reset = '<?php echo CHtml::activeId($shipment, 'receiver_name') ?>';
																							payer_address_reset = '<?php echo CHtml::activeId($shipment, 'receiver_address') ?>';
																							payer_country_reset = '<?php echo CHtml::activeId($shipment, 'receiver_country') ?>';
																							payer_postal_reset = '<?php echo CHtml::activeId($shipment, 'receiver_postal') ?>';
																							payer_phone_reset = '<?php echo CHtml::activeId($shipment, 'receiver_phone') ?>';
																							break;
																						case 'consignee': 
																							payer_name_reset = '<?php echo CHtml::activeId($shipment, 'shipper_name') ?>';
																							payer_address_reset = '<?php echo CHtml::activeId($shipment, 'shipper_address') ?>';
																							payer_phone_reset = '<?php echo CHtml::activeId($shipment, 'shipper_phone') ?>';
																							payer_country_reset = '<?php echo CHtml::activeId($shipment, 'shipper_country') ?>';
																							payer_postal_reset = '<?php echo CHtml::activeId($shipment, 'shipper_postal') ?>';
																							payer_name = '<?php echo CHtml::activeId($shipment, 'receiver_name') ?>';
																							payer_address = '<?php echo CHtml::activeId($shipment, 'receiver_address') ?>';
																							payer_phone = '<?php echo CHtml::activeId($shipment, 'receiver_phone') ?>';
																							payer_country = '<?php echo CHtml::activeId($shipment, 'receiver_country') ?>';
																							payer_postal = '<?php echo CHtml::activeId($shipment, 'receiver_postal') ?>';
																							break;
																						case '3rdparty': 
																							break;
																						}
																						formPanel.down('#'+'<?php echo CHtml::activeId($shipment, 'customer_id') ?>').setValue(res.data.customer_id);
																						formPanel.down('#'+payer_name).setValue(res.data.name);
																						formPanel.down('#'+payer_name_reset).reset();
																						formPanel.down('#'+payer_address).setValue(res.data.address);
																						formPanel.down('#'+payer_address_reset).reset();
																						formPanel.down('#'+payer_phone).setValue(res.data.phone);
																						formPanel.down('#'+payer_phone_reset).reset();
																						formPanel.down('#'+payer_country).setValue(res.data.country);
																						formPanel.down('#'+payer_country_reset).reset();
																						formPanel.down('#'+payer_postal).setValue(res.data.postal);
																						formPanel.down('#'+payer_postal_reset).reset();
																					}
																					else
																						Ext.MessageBox.alert('Customer data not available');

																				}
																			});
																		}
																	}
																},
																x:5,
																y:65
															}
														]
													}
												]
											},
											{
												xtype:'panel',
												flex:.30,
												layout:{type:'hbox'},
												height:150,
												border:false,
												items :[
													{
														xtype:'panel',
														height:140,
														width:'50%',
														title:'ORIGIN',
														layout:{type:'absolute'},
														items:[
															{
																xtype:'textfield',
																anchor:'95%',
																x:10,
																y:10
															},
															{
																xtype : 'label',
																style : 'margin-bottom:5px',
																width : '100%',
																html : '<hr>',
																y:'40%'
															},
															{
																xtype:'textfield',
																labelSeparator:'',
																labelAlign:'top',
																fieldLabel:'Courier',
																anchor:'95%',
																x:10,
																y:60
															}
														]
													},
													{
														xtype:'panel',
														height:140,
														width:'50%',
														title:'DESTINATION',
														layout:{type:'absolute'},
														items:[
															{
																xtype:'textfield',
																x:10,
																y:10,
																value:<?php echo CJSON::encode($shipment->destination_code) ?>,
																allowBlank:false,
																anchor:'95%',
																id:<?php echo CJSON::encode(CHtml::activeId($shipment, 'destination_code')) ?>,
																name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'destination_code')) ?>,
																listeners:{
																	focus:function(){
																		var product;
																		var dest = formPanel.down('#<?php echo CHtml::activeId($shipment, 'destination_code') ?>');
																		var val_product = Ext.getCmp('<?php echo CHtml::activeId($shipment, 'service_type') ?>').getValue();
																		for(var val in val_product)
																			product = val_product[val];
																		var receiver_postal = formPanel.down('#<?php echo CHtml::activeId($shipment, 'receiver_postal') ?>').getValue();
																	
																		if(!product)
																			Ext.MessageBox.alert('No Service Available');
																		else
																		{
																			Ext.Ajax.request({
																				method:'POST',
																				url: '<?php echo $this->createUrl('shipment/getExtAltRoutingCode') ?>',
																				params: {product : product,receiver_postal:receiver_postal},
																				success: function(result, request){
																					var res = Ext.decode(result.responseText);
																					if(res.status == 'success')
																					{
																						var sStore = Ext.data.StoreManager.lookup('routingStore');
																						sStore.loadRawData(res.data);
																						var win_routing = Ext.create('widget.window',{
																							title: 'Available Routing',
																							closable: true,
																							modal:true,
																							closeAction: 'hide',
																							layout: {
																								type: 'auto'
																							},
																							items:[
																								{
																									xtype:'grid',
																									listeners:{
																										itemdblclick:function(grid, record) {
																											dest.setValue(record.get('code'));
																											win_routing.hide();
																										}
																									},
																									store :Ext.data.StoreManager.lookup('routingStore'),
																									columns:
																										[{text:'Routing',dataIndex:'code',width:130,align:'center'}]		
																								}
																							]
																						}).show();
																					}
																				}
																			});
																		}	
																	}
																}
															},
															{
																xtype : 'label',
																style : 'margin-bottom:5px',
																width : '100%',
																html : '<hr>',
																y:'40%'
															},
															{
																xtype:'textfield',
																labelSeparator:'',
																labelAlign:'top',
																fieldLabel:'Courier',
																anchor:'95%',
																x:10,
																y:60
															}
														]
													}
												]
											},
											{
												xtype:'panel',
												flex:.35,
												height:150,
												layout:'absolute',
												border:false,
												items :[
													{
														xtype:'button',
														text:'Generate Waybill',
														x:60,
														y:30,
														listeners:
														{
															click:function(){
																Ext.Ajax.request({
																	url: '<?php echo $this->createUrl('shipment/getAwbNumber') ?>',
																	method:'POST',
																	success: function ( result, request ) {
																		var res = Ext.decode(result.responseText);
																		formPanel.down('#<?php echo CHtml::activeId($shipment, 'awb') ?>').setValue(res.data);
																	}
																})
															}
														}
													},
													{
														xtype:'textfield',
														labelAlign:'top',
														labelSeparator:'',
														fieldLabel:'Shipment Waybill',
														name : <?php echo CJSON::encode(CHtml::activeName($shipment, 'awb')) ?>,
														height:80,
														y:70,
														x:60,
														width:'85%',
														itemId:<?php echo CJSON::encode(CHtml::activeId($shipment, 'awb')) ?>,
														allowBlank: false,
														value:<?php echo CJSON::encode($shipment->awb) ?>
													}
												]
											}
										]
									},
									{
										xtype:'panel',
										layout:{type:'hbox'},
										width:'100%',
										height:300,
										border:false,
										items :[
											{
												xtype:'panel',
												flex:.50,
												height:300,
												title:'SHIPPER DETAIL',
												layout:{type:'vbox'},
												items:[
													{
														xtype:'panel',
														height:60,
														width:'100%',
														layout:{type:'hbox'},
														border:false,
														items:[
															{
																xtype:'panel',
																flex:.50,
																height:60,
																layout:'absolute',
																items:[
																	{
																		xtype:'label',
																		text:'Shipper Account / Company Name',
																		x:5,
																		y:5
																	},
																	{
																		xtype:'textfield',
																		anchor:'33%',
																		x:5,
																		y:24
																	},
																	{
																		xtype:'textfield',
																		name : <?php echo CJSON::encode(CHtml::encode(CHtml::activeName($shipment, 'shipper_company_name'))) ?>,
																		anchor:'60%',
																		x:'34%',
																		y:24
																	}
																]
															},
															{
																xtype:'panel',
																flex:.50,
																height:60,
																layout:'absolute',
																items:[
																	{
																		xtype:'textfield',
																		labelSeparator:'',
																		fieldLabel:'Shipper Name',
																		labelAlign:'top',
																		itemId : <?php echo CJSON::encode(CHtml::activeId($shipment, 'shipper_name')) ?>,
																		name : <?php echo CJSON::encode(CHtml::activeName($shipment, 'shipper_name')) ?>,
																		anchor:'95%',
																		value:<?php echo CJSON::encode($shipment->shipper_name) ?>,
																		x:5,
																		y:5,
																		allowBlank: false
																	}
																]
															}
														]
													},
													{
														xtype:'panel',
														height:240,
														width:'100%',
														layout:{type:'absolute'},
														border:false,
														items:[
															{
																xtype:'textareafield',
																labelSeparator:'',
																labelAlign:'top',
																itemId:<?php echo CJSON::encode(CHtml::activeId($shipment, 'shipper_address')) ?>,
																fieldLabel:'Address',
																name : <?php echo CJSON::encode(CHtml::activeName($shipment, 'shipper_address')) ?>,
																anchor:'45%',
																x:5,
																y:5,
																allowBlank: false,
																value:<?php echo CJSON::encode($shipment->shipper_address) ?>
															},
															{
																xtype:'textfield',
																labelSeparator:'',
																labelAlign:'top',
																fieldLabel:'Phone',
																itemId:<?php echo CJSON::encode(CHtml::activeId($shipment, 'shipper_phone')) ?>,
																name : <?php echo CJSON::encode(CHtml::activeName($shipment, 'shipper_phone')) ?>,
																width:150,
																x:'48%',
																y:5,
																value:<?php echo CJSON::encode($shipment->shipper_phone) ?>
															},
															{
																xtype:'datefield',
																labelSeparator:'',
																labelAlign:'top',
																width:135,
																x:'75%',
																y:5,
																fieldLabel:'Date-Time',
																format:'d/m/y H:i:s'
															},
															{
																xtype:'textfield',
																labelSeparator:'',
																fieldLabel:'City',
																labelWidth:60,
																name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'shipper_city')) ?>,
																width:265,
																x:5,
																y:'60%',
																value:<?php echo CJSON::encode($shipment->shipper_city) ?>
															},
															{
																xtype:'textfield',
																labelSeparator:'',
																fieldLabel:'Country',
																itemId : <?php echo CJSON::encode(CHtml::activeId($shipment, 'shipper_country')) ?>,
																name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'shipper_country')) ?>,
																labelWidth:60,
																value:<?php echo CJSON::encode($shipment->shipper_country) ?>,
																width:265,
																x:5,
																y:'75%',
																allowBlank: false
															},
															{
																xtype:'numberfield',
																labelSeparator:'',
																fieldLabel:'Postal Code',
																itemId : <?php echo CJSON::encode(CHtml::activeId($shipment, 'shipper_postal')) ?>,
																name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'shipper_postal')) ?>,
																labelWidth:70,
																width:145,
																x:'47%',
																y:'60%',
																maxLength:5,
																minLength:5,
																enforceMaxLength:true,
																allowBlank: false,
																value:<?php echo CJSON::encode($shipment->shipper_postal); ?>
															},
															{
																xtype:'label',
																text:'Shipper\'s Name & Signature',
																x:'73%',
																y:'80%'
															}
														]
													}
												]
											},
											{
												xtype:'panel',
												flex:.50,
												height:300,
												title:'CONSIGNEE DETAIL',
												layout:{type:'vbox'},
												items:[
													{
														xtype:'panel',
														height:60,
														width:'100%',
														layout:{type:'hbox'},
														border:false,
														items:[
															{
																xtype:'panel',
																flex:.50,
																height:60,
																layout:'absolute',
																items:[
																	{
																		xtype:'textfield',
																		labelSeparator:'',
																		labelAlign:'top',
																		fieldLabel:'Company Name',
																		name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'receiver_company_name')) ?>,
																		anchor:'60%',
																		x:'34%',
																		y:24,
																		value:<?php echo CJSON::encode($shipment->receiver_company_name) ?>,
																		anchor:'95%',
																		x:5,
																		y:5
																	}
																]
															},
															{
																xtype:'panel',
																flex:.50,
																height:60,
																layout:'absolute',
																items:[
																	{
																		xtype:'textfield',
																		labelSeparator:'',
																		labelAlign:'top',
																		fieldLabel:'Attention of',
																		name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'receiver_name')) ?>,
																		itemId:<?php echo CJSON::encode(CHtml::activeId($shipment, 'receiver_name')) ?>,
																		anchor:'95%',
																		x:5,
																		y:5,
																		allowBlank: false,
																		value:<?php echo CJSON::encode($shipment->receiver_name) ?>
																	}
																]
															}
														]
													},
													{
														xtype:'panel',
														height:240,
														width:'100%',
														layout:{type:'absolute'},
														border:false,
														items:[
															{
																xtype:'textareafield',
																labelSeparator:'',
																labelAlign:'top',
																fieldLabel:'Address',
																itemId:<?php echo CJSON::encode(CHtml::activeId($shipment, 'receiver_address')) ?>,
																name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'receiver_address')) ?>,
																anchor:'45%',
																x:5,
																y:5,
																allowBlank: false,
																value:<?php echo CJSON::encode($shipment->receiver_address) ?>
															},
															{
																xtype:'textfield',
																labelSeparator:'',
																labelAlign:'top',
																fieldLabel:'Phone',
																itemId:<?php echo CJSON::encode(CHtml::activeId($shipment, 'receiver_phone')) ?>,
																name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'receiver_phone')) ?>,
																width:150,
																x:'48%',
																y:5,
																value:<?php echo CJSON::encode($shipment->receiver_phone) ?>
															},
															{
																xtype:'datefield',
																labelSeparator:'',
																labelAlign:'top',
																width:135,
																x:'75%',
																y:5,
																fieldLabel:'Date-Time',
																format:'d/m/y H:i:s'
															},
															{
																xtype:'textfield',
																labelSeparator:'',
																fieldLabel:'City',
																name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'receiver_city')) ?>,
																labelWidth:60,
																width:265,
																x:5,
																y:'60%',
																value:<?php echo CJSON::encode($shipment->receiver_city) ?>
															},
															{
																xtype:'textfield',
																labelSeparator:'',
																fieldLabel:'Country',
																name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'receiver_country')) ?>,
																id:<?php echo CJSON::encode(CHtml::activeId($shipment, 'receiver_country')) ?>,
																labelWidth:60,
																width:265,
																x:5,
																y:'75%',
																allowBlank: false,
																value:<?php echo CJSON::encode($shipment->receiver_country) ?>,
																listeners:
																	{
																	blur:function(){
																		var receiver_country = this.getValue();
																		var receiver_postal = Ext.getCmp('<?php echo CHtml::activeId($shipment, 'receiver_postal') ?>').getValue();
																		var destination = Ext.getCmp('<?php echo CHtml::activeId($shipment, 'destination_code') ?>');
																		getRoutingCode(receiver_country,receiver_postal,destination,'<?php echo $this->createUrl('shipment/getExtRoutingCode') ?>');
																	}
																}
															},
															{
																xtype:'numberfield',
																labelSeparator:'',
																fieldLabel:'Postal Code',
																name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'receiver_postal')) ?>,
																id:<?php echo CJSON::encode(CHtml::activeId($shipment, 'receiver_postal')) ?>,
																labelWidth:70,
																width:145,
																maxLength:5,
																minLength:5,
																enforceMaxLength:true,
																allowBlank:false,
																x:'47%',
																y:'60%',
																value:<?php echo CJSON::encode($shipment->receiver_postal) ?>,
																listeners:
																	{
																	blur:function(){
																		var receiver_country = Ext.getCmp('<?php echo CHtml::activeId($shipment, 'receiver_country') ?>').getValue();
																		var receiver_postal = this.getValue();
																		var destination = Ext.getCmp('<?php echo CHtml::activeId($shipment, 'destination_code') ?>');
																		getRoutingCode(receiver_country,receiver_postal,destination,'<?php echo $this->createUrl('shipment/getExtRoutingCode') ?>');
																	}
																}
															},
															{
																xtype:'label',
																text:'Shipper\'s Name & Signature',
																x:'73%',
																y:'80%'
															}
														]
													}
												]
											}
										]
									},
									{
										xtype:'panel',
										layout:{type:'hbox'},
										width:'100%',
										height:150,
										border:false,
										items :[
											{
												xtype:'panel',
												flex:.25,
												height:150,
												title:'SHIPMENT DETAIL',
												layout:'vbox',
												autoSize:true,
												items:[
													{
														xtype:'panel',
														width:'100%',
														height:65,
														layout:'hbox',
														border:false,
														items:[
															{
																xtype:'panel',
																flex:.50,
																height:65,
																layout:'absolute',
																items:[
																	{
																		xtype:'numberfield',
																		labelSeparator:'',
																		labelAlign:'top',
																		fieldLabel:'Number of Package',
																		anchor:'95%',
																		id:<?php echo CJSON::encode(CHtml::activeId($shipment, 'pieces')) ?>,
																		name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'pieces')) ?>,
																		x:5,
																		y:5
																	}
																]
															},
															{
																xtype:'panel',
																flex:.50,
																height:65,
																layout:'absolute',
																items:[
																	{
																		xtype:'textfield',
																		labelSeparator:'',
																		labelAlign:'top',
																		fieldLabel:'Total Weight',
																		name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'package_weight')) ?>,
																		id:<?php echo CJSON::encode(CHtml::activeId($shipment, 'package_weight')) ?>,
																		anchor:'95%',
																		allowBlank: false,
																		x:5,
																		y:5,
																		value:<?php echo CJSON::encode($shipment->package_weight) ?>
																	}
																]
															}
														]
													},
													{
														xtype:'panel',
														border:false,
														width:'100%',
														height:65,
														layout:'hbox',
														items:
															[
															{
																xtype : 'panel',
																layout : 'absolute',
																flex : .50,
																height:65,
																items:
																	[
																	{
																		xtype:'label',
																		text:'Dimension',
																		x:5,
																		y:5
																	},
																	{
																		xtype:'textfield',
																		labelSeparator:'',
																		emptyText:'P',
																		submitEmptyText : false,
																		width:30,
																		x:5,
																		y:25
																	},
																	{
																		xtype:'label',
																		text:'X',
																		x:40,
																		y:30
																	},
																	{
																		xtype:'textfield',
																		labelSeparator:'',
																		emptyText:'L',
																		submitEmptyText : false,
																		width:30,
																		x:52,
																		y:25
																	},
																	{
																		xtype:'label',
																		text:'X',
																		x:87,
																		y:30
																	},
																	{
																		xtype:'textfield',
																		labelSeparator:'',
																		emptyText:'T',
																		submitEmptyText : false,
																		width:30,
																		x:99,
																		y:25
																	}
																]
															},
															{
																xtype : 'panel',
																layout : 'absolute',
																flex : .50,
																height:65,
																items:
																	[
																	{
																		xtype: 'radiogroup',
																		id:<?php echo CJSON::encode(CHtml::activeId($shipment, 'type')) ?>,
																		labelSparator:'',
																		fieldLabel: '',
																		columns:1,
																		allowBlank: false,
																		items: [
																			{
																				boxLabel: 'Document',
																				name: <?php echo CJSON::encode(CHtml::activeName($shipment, 'type')) ?>,
																				inputValue: 'document',
																				checked:<?php echo json_encode($shipment->type == 'document') ?>
																			},
																			{
																				boxLabel: 'Package',
																				name: <?php echo CJSON::encode(CHtml::activeName($shipment, 'type')) ?>,
																				inputValue: 'parcel',
																				checked:<?php echo json_encode($shipment->type == 'parcel') ?>
																			}
																		]
																	}
																]
															}
														]
													}
												]
                                                        
											},
											{
												xtype:'panel',
												flex:.25,
												height:150,
												title:'DESCRIPTION OF GOODS',
												layout:'vbox',
												autosize:true,
												items:[
													{
														xtype:'panel',
														width:'100%',
														height:65,
														layout:'absolute',
														items:[
															{
																xtype:'textareafield',
																labelSeparator:'',
																anchor:'95%',
																height:55,
																x:5,
																y:3
															}
														]
													},
													{
														xtype:'panel',
														width:'100%',
														height:65,
														layout:'absolute',
														items:[
															{
																xtype:'textfield',
																fieldLabel:'Declare Value',
																labelSeparator:'',
																labelAlign:'top',
																name : <?php echo CJSON::encode(CHtml::activeName($shipment, 'package_value')) ?>,
																x:5,
																y:5
															},
															{
																xtype: 'checkbox',
																fieldLabel: 'Insured',
																labelSeparator : '',
																labelWidth : 50,
																columns: 1,
																items: [
																	{boxLabel: '', name: <?php echo CJSON::encode(CHtml::activeName($shipment, 'insurance')) ?>}
																],
																x:150,
																y:2
															},
															{
																xtype: 'checkbox',
																fieldLabel: 'Fragile',
																labelSeparator : '',
																labelWidth : 50,
																columns: 1,
																items: [
																	{boxLabel: '', name: <?php echo CJSON::encode(CHtml::activeName($shipment, 'fragile')) ?>}
																],
																x:230,
																y:2
															}
														]
													}
												]
											},
											{
												xtype:'panel',
												flex:.25,
												height:150,
												title:'PRODUCT & SERVICES',
												layout:'hbox',
												items:[
													{
														xtype:'panel',
														flex:.50,
														height:150,
														layout:'absolute',
														items:[
															{
																xtype: 'radiogroup',
																columns:1,
																id:'<?php echo CHtml::activeId($shipment, 'service_type') ?>',
																labelSparator:'',
																fieldLabel: '',
																vertical: false,
																items: <?php echo Product::getExtProducts($shipment); ?>,
																x:10,
																y:10,
																allowBlank:false
															}
														]
													},
													{
														xtype:'panel',
														flex:.50,
														height:150,
														layout:'absolute',
														items:[
															{
																xtype:'hiddenfield',
																name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'service_id')) ?>,
																id:<?php echo CJSON::encode(CHtml::activeId($shipment, 'service_id')) ?>,
																value:<?php echo CJSON::encode($shipment->service_id) ?>
															},
															{
																xtype:'textfield',
																allowBlank:false,
																labelSeparator:'',
																labelAlign:'top',
																fieldLabel:'Service Code',
																anchor:'95%',
																id:<?php echo CJSON::encode(CHtml::activeId($shipment, 'service_code')) ?>,
																name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'service_code')) ?>,
																value:<?php echo CJSON::encode($shipment->service_code) ?>,
																listeners:{
																	focus:function(){
																		var that = this;
																		var customer_id = formPanel.down('#<?php echo Chtml::activeId($shipment, 'customer_id') ?>').getValue();
																		var shipper_country = formPanel.down('#<?php echo CHtml::activeId($shipment, 'shipper_country') ?>').getValue();
																		var receiver_country = formPanel.down('#<?php echo CHtml::activeId($shipment, 'receiver_country') ?>').getValue();
																		var product;
																		var routing_code = Ext.getCmp('<?php echo CHtml::activeId($shipment, 'destination_code') ?>').getValue();
																		var val_product = Ext.getCmp('<?php echo CHtml::activeId($shipment, 'service_type') ?>').getValue();
																		for(var val in val_product)
																			product = val_product[val];
																		
																		Ext.Ajax.request({
																			method:'POST',
																			url: '<?php echo $this->createUrl('shipment/getExtTypeService') ?>',
																			params: function(){
																				return "Shipment[destination_code]="+routing_code+"&Shipment[service_type]="+product+"&Shipment[receiver_country]="+receiver_country+"&Shipment[shipper_country]="+shipper_country+"&Shipment[customer_id]="+customer_id;
																			},
																			success: function ( result, request ) {
																				var res = Ext.decode(result.responseText);
																				if(res.status == 'error')
																					Ext.MessageBox.alert("No Service Available");
																				else if(res.status == 'success')
																				{
																					var sStore = Ext.data.StoreManager.lookup('serviceStore');
																					sStore.loadRawData(res.data);
																					var win_service = Ext.create('widget.window',{
																						title: 'Select Service',
																						closable: true,
																						modal:true,
																						closeAction: 'hide',
																						layout: {type: 'auto'},
																						listeners:{
																							hide:function(){
																								setPrice();
																							}
																						},
																						items:[
																							{
																								xtype:'grid',
																								listeners:{
																									itemdblclick:function(grid, record) {
																										that.setValue(record.get('code'));
																										formPanel.down('#<?php echo CHtml::activeId($shipment, 'service_id') ?>').setValue(record.get('service_id'));
																										win_service.hide();
																									}
																								},
																								store :Ext.data.StoreManager.lookup('serviceStore'),
																								columns:
																									[
																									{text:'Service Id',dataIndex:'service_id',hidden:true},
																									{text:'Service Name',dataIndex:'service_name',width:150},
																									{text:'Service Code',dataIndex:'code'},
																									{text:'Carrier Service',dataIndex:'carrier_service',width:150},
																									{text:'Vendor',dataIndex:'vendor_name'}
																								]		
																							}
																						]
																					}).show();
																				}
																			}
																		});
																	}
																},
																x:5,
																y:5
															}
														]
													}
												]
                                                        
											},
											{
												xtype:'panel',
												flex:.25,
												height:150,
												title:'SERVICE CHARGES',
												layout:'absolute',
												items:[
													{
														xtype:'textfield',
														labelWidth:120,
														fieldLabel:'1. Service Charge',
														anchor:'95%',
														id:<?php echo CJSON::encode(CHtml::activeId($shipment, 'shipping_charges')) ?>,
														name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'shipping_charges')) ?>,
														value:<?php echo CJSON::encode($shipment->shipping_charges) ?>,
														x:5,
														y:5
													},
													{
														xtype:'textfield',
														labelWidth:120,
														fieldLabel:'2. Insurance Charge',
														anchor:'95%',
														id:'insurance',
														x:5,
														y:35
													},
													{
														xtype:'textfield',
														labelWidth:120,
														fieldLabel:'3. Other Charge',
														anchor:'95%',
														id:'other',
														x:5,
														y:65
													},
													{
														xtype:'label',
														html:'<hr>',
														width:'100%',
														y:84
													},
													{
														xtype:'textfield',
														labelWidth:120,
														fieldLabel:'4. Total Charge',
														anchor:'95%',
														x:5,
														y:95
													}
												]
											}
										]
									}
								]                                        
							}
						],
						url: <?php echo CJSON::encode($this->createAbsoluteUrl('shipment/submitOrder')); ?>,
						buttons: [{
								text: 'Save',
								handler: function(){
									var form = formPanel.getForm();
									if(form.isValid()){
										form.submit({
											waitMsg:'Creating Order....',
											success: function(form, action){
												var data = action.result.message;
												var display = '<table>'+
													'<tr><td>Waybill&nbsp;</td><td>:</td>'+
													'<td>&nbsp;'+data.awb+'</td></tr>'+
													'</table>';
												
													var redirect = function (btn){
															window.location = '<?php echo $this->createAbsoluteUrl('booking/create')?>&shipment_id='+data.shipment_id;
													};
												
													Ext.MessageBox.alert('Success',display,redirect);
													
//												Ext.MessageBox.show({
//													msg: display,
//													title: "Success",
//													buttons: Ext.Msg.OK,
//													listners : {
//														click :  {
//															element: 'OK',
//															fn: function(){ 
//																window.location = '<?php echo $this->createAbsoluteUrl('booking/create')?>&shipment_id='+data.shipment_id;
//																console.log('<?php echo $this->createAbsoluteUrl('booking/create')?>&shipment_id='+data.shipment_id);
//																alert('tes');
//															}
//														}
//													}
//                        });
											},
											failure: function(form, action){
												Ext.MessageBox.alert('Failed', 'Order Failed');
											}
										});
									}
								}
							},{
								text: 'Reset',
								handler: function(){
									formPanel.getForm().reset();
									Ext.getCmp('insurance').setFieldLabel('2. Insurance Charge');
									Ext.getCmp('other').setFieldLabel('3. Other Charge');
								}
							}]
					});
					Ext.getCmp('insurance').getEl().on('dblclick', function(){
						winSurcharge.show();
					});
					Ext.getCmp('other').getEl().on('dblclick', function(){
						winOther.show();
					});
				});
		
	</script>

</div>