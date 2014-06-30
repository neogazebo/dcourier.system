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
			'Ext.state.*'
		]);
		
		Ext.onReady(function(){
			var carrierData = Ext.create('Ext.data.Store', {
				fields: ['id', 'code'],
				data : Ext.decode('<?php echo $shipment->GetExtCarrierData() ?>')
			});
			
			Ext.create('Ext.data.Store', {
				storeId:'serviceStore',
				fields:['service_id','service_name','code','carrier_service','vendor_name']
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
						layout:{type :'hbox'},
						items: [
							{
								xtype:'panel',
								flex:2,
								height:150,
								border:false
							},
							{
								xtype:'panel',
								flex:2,
								height:150,
								style:'margin :5px',
								layout:'auto',
								items:[{
										xtype:'panel',
										width:'auto',
										height : 'auto',
										layout :'hbox',
										border :false,
										items:[
											{
												xtype:'panel',
												flex:.30,
												height:76,
												border:false,
												layout:'absolute',
												items:[{
														xtype:'radiogroup',
														fieldLabel:'Charge To',
														labelSeparator: '',
														style : 'font: 8px',
														labelWidth : 60,
														style :'margin: 5px 0 0 8px',
														columns: 1,
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
																for(val in payers)
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
															}
														}]
												},
												{
													xtype:'panel',
													flex:.30,
													height:76,
													layout:'absolute',
													items :[
														{
															xtype: 'checkboxgroup',
															fieldLabel: 'Shipment Insurance',
															labelSeparator : '',
															labelWidth : 110,
															style :'margin: 5px 0 0 8px',
															columns: 1,
															items: [
																{boxLabel: '', name: <?php echo CJSON::encode(CHtml::activeName($shipment, 'insurance')) ?>}
															]
														},
														{
															xtype:'textfield',
															fieldLabel:'',
															labelSeparator : '',
															name : <?php echo CJSON::encode(CHtml::activeName($shipment, 'insurance_charge')) ?>,
															anchor:'95%',
															x:10,
															y:30
														},
														{
															xtype:'label',
															cls: 'x-form-item custFont',
															text :'Declare Value',
															x:10,
															y:55
														}
													]
												},
												{
													xtype:'panel',
													flex:.40,
													height:76,
													layout:'absolute',
													items:[
														{
															xtype:'textfield',
															labelAlign : 'top',
															fieldLabel:'AirwayBill Number',
															name : <?php echo CJSON::encode(CHtml::activeName($shipment, 'awb')) ?>,
															labelSeparator : '',
															anchor : '95%',
															x:10,
															y:10
														}
													]
												}
											]
										},
										{
											xtype:'panel',
											width:'auto',
											height : 'auto',
											layout :'hbox',
											border:false,
											items:[
												{
													xtype:'panel',
													flex:.60,
													height:70,
													border:false,
													layout:'absolute',
													items:[{
															xtype:'textfield',
															fieldLabel:'Payer Account No',
															itemId:<?php echo CJSON::encode(CHtml::activeId($customer, 'accountnr')) ?>,
															labelSeparator:'',
															x:10,
															y:45,
															anchor:'95%',
															enableKeyEvents : true,
															name : <?php echo CJSON::encode(CHtml::activeName($customer, 'accountnr')) ?>,
															listeners: {
																keypress: function(txt, e) {
																	if (e.keyCode == 13) {
																		Ext.Ajax.request({
																			method:'POST',
																			url: '<?php echo $this->createUrl('shipment/getExtCustomerData') ?>',
																			params: {
																				accountnr : this.getValue()
																			},
																			success: function ( result, request ) {
																				var res = Ext.decode(result.responseText);
																				formPanel.down('#<?php echo CHtml::activeId($shipment, 'shipper_name') ?>').setValue(res.name);
																				
																			}
																		});
																	}
																}
															}                                  
														}]
												},
												{
													xtype:'panel',
													flex:.20,
													height:70,
													layout:'absolute',
													items :[{
															xtype:'textfield',
															fieldLabel:'Origin',
															labelSeparator:'',
															labelAlign:'top',
															x:10,
															y:5,
															anchor:'95%'                             
														}]
												},
												{
													xtype:'panel',
													flex:.20,
													height:70,
													layout:'absolute',
													items:[{
															xtype:'combobox',
															fieldLabel:'Destination',
															id:'ship_dest',
															labelSeparator:'',
															labelAlign:'top',
															x:10,
															y:5,
															anchor:'95%',
															displayField: 'code',
															valueField: 'id',
															store : carrierData,
															listeners:{
																change:function(field, newVal, oldVal){
																	Ext.Ajax.request({
																		method:'POST',
																		url: '<?php echo $this->createUrl('shipment/getExtAvailableProduct') ?>',
																		params: {
																			company_id : this.getValue()
																		},
																		success: function ( result, request ) {
																			var res = Ext.decode(result.responseText);
																			Ext.getCmp(res.data.service_type).setValue(true);
																		}
																	});
																}
															}
														}]
												}
											]
										}
									]
								}
							]
						},
						{
							xtype:'panel',
							layout:{type :'hbox'},
							border:false,
							items: [
								{
									xtype:'panel',
									flex:2,
									height:455,
									layout:{type:'vbox'},
									autoSize:true,
									title:'Shipper\'s Detail',
									border : false,
									items:[
										{
											xtype: 'panel',
											layout:{type:'hbox'},
											width: '100%',
											height: 60,
											border:false,
											items:[
												{
													xtype:'panel',
													flex:.40,
													height:60,
													autoSize:true,
													layout:'absolute',
													items:[
														{
															xtype:'textfield',
															labelSeparator:'',
															alias : 'shipper_name',
															itemId : <?php echo CJSON::encode(CHtml::activeId($shipment, 'shipper_name')) ?>,
															labelAlign:'top',
															fieldLabel:'Shipper\'s Name',
															anchor :'95%',
															name : <?php echo CJSON::encode(CHtml::activeName($shipment, 'shipper_name')) ?>,
															x:5,
															y:5
														}
													]
												},
												{
													xtype:'panel',
													flex:.60,
													height:60,
													autoSize:true,
													layout:'absolute',
													items:[
														{
															xtype:'textfield',
															labelSeparator:'',
															labelAlign:'top',
															fieldLabel:'Company Name',
															name : <?php echo CJSON::encode(CHtml::encode(CHtml::activeName($shipment, 'shipper_company_name'))) ?>,
															anchor :'95%',
															x:5,
															y:5
														}
													]
												}
											]
										},
										{
											xtype: 'panel',
											layout:{type:'hbox'},
											width: '100%',
											height: 200,
											border :false,
											items:[
												{
													xtype:'panel',
													flex:.70,
													height:200,
													autoSize:true,
													layout:'absolute',
													items:[
														{
															xtype:'textareafield',
															labelAlign:'top',
															itemId:<?php echo CJSON::encode(CHtml::activeId($shipment, 'shipper_address')) ?>,
															fieldLabel:'Address',
															name : <?php echo CJSON::encode(CHtml::activeName($shipment, 'shipper_address')) ?>,
															labelSeparator:'',
															anchor:'55%',
															height:180,
															x:5,
															y:5
														},
														{
															xtype:'textfield',
															labelAlign:'top',
															labelSeparator:'',
															fieldLabel:'Country',
															itemId : <?php echo CJSON::encode(CHtml::activeId($shipment, 'shipper_country')) ?>,
															name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'shipper_country')) ?>,
															anchor:'40%',
															x:'58%',
															y:5
														},
														{
															xtype:'textfield',
															labelAlign:'top',
															labelSeparator:'',
															fieldLabel:'Post Code',
															itemId : <?php echo CJSON::encode(CHtml::activeId($shipment, 'shipper_postal')) ?>,
															name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'shipper_postal')) ?>,
															enforceMaxLength:true,
															maxLength:5,
															name : '',
															anchor:'40%',
															x:'58%',
															y:50
														},
														{
															xtype:'textfield',
															labelAlign:'top',
															itemId:<?php echo CJSON::encode(CHtml::activeId($shipment, 'shipper_phone')) ?>,
															labelSeparator:'',
															fieldLabel:'Phone',
															name : <?php echo CJSON::encode(CHtml::activeName($shipment, 'shipper_phone')) ?>,
															anchor:'40%',
															x:'58%',
															y:100
														}
													]
												},
												{
													xtype:'panel',
													flex:.30,
													height:200,
													autoSize:true,
													layout:'absolute',
													items:[
														{
															xtype:'datefield',
															labelSeparator:'',
															labelAlign:'top',
															anchor :'95%',
															x:5,
															y:5,
															fieldLabel:'Date'
														},
														{
															xtype:'label',
															text:'Shipper\'s Name \& Signature',
															x:15,
															y:'85%'
														}
													]
												}
											]
										},
										{
											xtype: 'panel',
											type:'column',
											width: '100%',
											height: 40,
											border:false,
											items:[
												{
													xtype: 'radiogroup',
													id:'<?php echo CHtml::activeId($shipment, 'shipment_type') ?>',
													labelSparator:'',
													fieldLabel: '',
													vertical: false,
													items: <?php echo Product::getExtProducts(); ?>
												}
											]
										},
										{
											xtype: 'panel',
											layout:{type:'hbox'},
											width: '100%',
											height: 70,
											border:false,
											items:[
												{
													xtype:'panel',
													flex:.30,
													height:70,
													layout:'absolute',
													items:[
														{
															xtype:'textfield',
															labelSeparator:'',
															labelAlign:'top',
															fieldLabel:'PickUp By',
															anchor:'95%',
															x:5,
															y:5
														}
													]
												},
												{
													xtype:'panel',
													flex:.10,
													height:70,
													layout:'absolute',
													items :[
														{
															xtype:'textfield',
															labelSeparator:'',
															labelAlign:'top',
															fieldLabel:'Weight',
															anchor:'95%',
															x:5,
															y:5
														}
													]
												},
												{
													xtype:'panel',
													flex:.10,
													height:70,
													layout:'absolute',
													items :[
														{
															xtype:'textfield',
															labelSeparator:'',
															labelAlign:'top',
															fieldLabel:'Pieces',
															anchor:'95%',
															x:5,
															y:5
														}
													]
												},
												{
													xtype:'panel',
													flex:.20,
													height:70,
													layout:'absolute',
													items :[
														{
															xtype:'hiddenfield',
															name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'service_id')) ?>,
															id:<?php echo CJSON::encode(CHtml::activeId($shipment, 'service_id')) ?>
														},
														{
															xtype:'textfield',
															labelSeparator:'',
															id:'type_of_service',
															labelAlign:'top',
															listeners:{
																focus:function(){
																	var that = this;
																	var val;
																	var product;
																	var rate_company_id = Ext.getCmp('ship_dest').getValue();
																	var val_product = Ext.getCmp('<?php  echo CHtml::activeId($shipment, 'shipment_type')   ?>').getValue();
																	for(val in val_product)
																		product = val_product[val];
																	
																	Ext.Ajax.request({
																		method:'POST',
																		url: '<?php echo $this->createUrl('shipment/getExtTypeService') ?>',
																		params: {
																			product : product,
																			rate_company_id : rate_company_id
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
																					closeAction: 'hide',
																					width: 600,
																					minWidth: 350,
																					height: 350,
																					layout: {
																						type: 'border'
																					},
																					items:[
																						{
																							xtype:'grid',
																							listeners:{
																								itemdblclick:function(grid, record) {
																										console.log('Double clicked on ' + record.get('service_id'));
																										console.log('Double clicked on ' + record.get('code'));
																										that.setValue(record.get('code'));
																										win_service.hide();
																								}
																							},
																							title:'Service List',
																							store :Ext.data.StoreManager.lookup('serviceStore'),
																							columns:
																							[
																								{text:'Service Id',dataIndex:'service_id'},
																								{text:'Service Name',dataIndex:'service_name'},
																								{text:'Service Code',dataIndex:'code'},
																								{text:'Carrier Service',dataIndex:'carrier_service'},
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
															fieldLabel:'Type Of Service',
															anchor:'95%',
															x:5,
															y:5
														}
													]
												},
												{
													xtype:'panel',
													flex:.30,
													height:70,
													layout:'absolute',
													items :[
														{
															xtype:'textfield',
															labelSeparator:'',
															labelAlign:'top',
															fieldLabel:'Type Of Shipment',
															anchor:'95%',
															x:5,
															y:5
														},
														{
															xtype: 'radiogroup',
															labelSparator:'',
															fieldLabel: '',
															layout:'absolute',
															items: [
																{boxLabel: 'Document', name: 'rb-auto', inputValue: 1,x:5,y:45},
																{boxLabel: 'Package', name: 'rb-auto', inputValue: 2,x:100,y:45}
															]
														}
													]
												}
											]
										},
										{
											xtype: 'panel',
											layout:{type:'hbox'},
											width: '100%',
											height: 60,
											border:false,
											items:[
												{
													xtype:'panel',
													flex:.13,
													height:60,
													layout:'absolute',
													items: [
														{
															xtype:'timefield',
															labelSeparator:'',
															labelAlign:'top',
															fieldLabel:'Time',
															format:'H:i',
															anchor:'95%',
															x:5,
															y:5
														}
													]
												},
												{
													xtype:'panel',
													flex:.17,
													height:60,
													layout:'absolute',
													items :[
														{
															xtype:'datefield',
															labelSeparator:'',
															labelAlign:'top',
															fieldLabel:'Date',
															anchor:'95%',
															format:'d/m/Y',
															x:5,
															y:5
														}
													]
												},
												{
													xtype:'panel',
													flex:.20,
													height:60,
													layout:'absolute',
													items :[
														{
															xtype:'textfield',
															labelAlign:'top',
															labelSeparator:'',
															fieldLabel:'Volumetric',
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
													autoSize:true,
													items :[
														{
															xtype:'textareafield',
															labelAlign:'top',
															fieldLabel:'Full Description Of Content',
															labelSeparator:'',
															anchor:'95%',
															height:85,
															x:5,
															y:5
														}
													]
												}
											]
										}
									]
								},
								{
									xtype:'panel',
									flex:2,
									height:455,
									layout:{type:'vbox'},
									autoSize:true,
									title:'Consignee\'s Detail',
									border:false,
									items:[
										{
											xtype: 'panel',
											layout:{type:'hbox'},
											width: '100%',
											height: 60,
											border:false,
											items :[
												{
													xtype:'panel',
													flex:.55,
													height:60,
													layout:'absolute',
													autoSize:true,
													items :[
														{
															xtype:'textfield',
															labelAlign:'top',
															labelSeparator:'',
															fieldLabel:'Company Name',
															name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'receiver_company_name')) ?>,
															anchor:'95%',
															x:5,
															y:5
														}
													]
												},
												{
													xtype:'panel',
													flex:.45,
													height:60,
													layout:'absolute',
													autoSize:true,
													items :[
														{
															xtype:'textfield',
															labelAlign:'top',
															labelSeparator:'',
															fieldLabel:'Attention Of',
															name:<?php echo CJSON::encode(CHtml::activeId($shipment, 'receiver_name')) ?>,
															itemId:<?php echo CJSON::encode(CHtml::activeId($shipment, 'receiver_name')) ?>,
															anchor :'95%',
															x:5,
															y:5
														}
													]
												}
											]
										},
										{
											xtype: 'panel',
											layout:{type:'hbox'},
											width: '100%',
											height: 200,
											border:false,
											items :[
												{
													xtype:'panel',
													flex:.70,
													height:200,
													layout:'absolute',
													autoSize:true,
													items : [
														{
															xtype:'textareafield',
															labelAlign:'top',
															fieldLabel:'Address',
															itemId:<?php echo CJSON::encode(CHtml::activeId($shipment, 'receiver_address')) ?>,
															labelSeparator:'',
															anchor:'55%',
															height:180,
															name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'receiver_address')) ?>,
															x:5,
															y:5
														},
														{
															xtype:'textfield',
															labelAlign:'top',
															labelSeparator:'',
															fieldLabel:'Country',
															name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'receiver_country')) ?>,
															itemId:<?php echo CJSON::encode(CHtml::activeId($shipment, 'receiver_country')) ?>,
															anchor:'40%',
															x:'58%',
															y:5
														},
														{
															xtype:'textfield',
															labelAlign:'top',
															labelSeparator:'',
															fieldLabel:'Post Code',
															name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'receiver_postal')) ?>,
															itemId:<?php echo CJSON::encode(CHtml::activeId($shipment, 'receiver_postal')) ?>,
															enforceMaxLength:true,
															maxLength:5,
															anchor:'40%',
															x:'58%',
															y:50
														},
														{
															xtype:'textfield',
															labelAlign:'top',
															itemId:<?php echo CJSON::encode(CHtml::activeId($shipment, 'receiver_phone')) ?>,
															labelSeparator:'',
															fieldLabel:'Phone',
															name:<?php echo CJSON::encode(CHtml::activeName($shipment, 'receiver_phone')) ?>,
															anchor:'40%',
															x:'58%',
															y:100
														}
													]
												},
												{
													xtype:'panel',
													flex:.30,
													height:200,
													layout:'absolute',
													autoSize:true,
													items :[
														{
															xtype:'radiogroup',
															fieldLabel:'Received in Good and Complete Condition',
															labelSeparator: '',
															labelWidth : 120,
															labelCls:'custFont',
															columns: 1,
															items: [
																{boxLabel: 'Yes', name: 'rb-col', inputValue: 1},
																{boxLabel: 'No', name: 'rb-col', inputValue: 2}
															],
															x:5,
															y:5
														},
														{
															xtype:'datefield',
															labelSeparator:'',
															labelAlign:'top',
															anchor :'95%',
															x:5,
															y:'25%',
															fieldLabel:'Date-Time',
															format:'d/m/y H:i:s'
														},
														{
															xtype:'label',
															text:'Shipper\'s Name \& Signature',
															x:15,
															y:'85%'
														}
													]
												}
											]
										},
										{
											xtype: 'panel',
											layout:{type:'hbox'},
											width: '100%',
											height: 170,
											items :[
												{
													xtype:'panel',
													flex:.55,
													height:170,
													layout:'vbox',
													autoSize:true,
													border:false,
													items :[
														{
															xtype: 'panel',
															layout:'auto',
															width: '100%',
															height: 110,
															items :[
																{
																	xtype:'textfield',
																	labelWidth:120,
																	fieldLabel:'1. Service Charge',
																	width :'95%',
																	style :'margin: 12px 5px 7px 5px'
																},
																{
																	xtype:'textfield',
																	labelWidth:120,
																	fieldLabel:'2. Insurance Charge',
																	width :'95%',
																	style :'margin: 12px 5px 7px 5px'
																},
																{
																	xtype:'textfield',
																	labelWidth:120,
																	fieldLabel:'3. Other Charge',
																	width :'95%',
																	style :'margin: 12px 5px 7px 5px'
																}
															]
														},
														{
															xtype: 'panel',
															layout:'auto',
															width: '100%',
															height: 30,
															items :[
																{
																	xtype:'textfield',
																	labelWidth:120,
																	fieldLabel:'4. Total Charge',
																	width :'95%',
																	style :'margin: 2px 5px 2px 5px'
																}
															]
														},
														{
															xtype: 'panel',
															layout:'auto',
															width: '100%',
															height: 30,
															items :[
																{
																	xtype: 'radiogroup',
																	fieldLabel: 'Payment',
																	labelWidth:120,
																	style:'margin: 2px 5px 2px 5px',
																	items: [
																		{boxLabel: 'Cash', name: 'rb-auto', inputValue: 1,width:70},
																		{boxLabel: 'Card', name: 'rb-auto', inputValue: 2,width:70},
																		{boxLabel: 'COD', name: 'rb-auto', inputValue: 3,width:70}
																	]
																}
															]
														}
													]
												},
												{
													xtype:'panel',
													flex:.45,
													height:170,
													layout:'absolute',
													autoSize:true,
													border:false,
													items :[
														{
															xtype:'label',
															style:'margin : 5px',
															cls: 'x-form-item description',
															html: 'PERHATIAN<br/>Dengan ini kami menerima semua\n\
                                                                            <br/>Persyaratan/ketentuan yang tercantum di balik\
                                                                            <br/>Airwaybill ini. Untuk pengiriman yang mempunyai nilai di atas Rp.500.000,- wajib untuk\n\
                                                                            <br/>diasuransikan. Apabila barang tidak <br/> diasuransikan KIRIM.CO.ID hanya akan\n\
                                                                            <br/>memberikan ganti rugi untuk setiap barang yang\n\
                                                                            <br/>rusak ataupun hilang, maksimal 10(sepuluh) kali\n\
                                                                            <br/> biaya kirim yang telah dibayar pengirim'
														}
													]
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
											Ext.MessageBox.alert('Success',action.result.msg);
										}
									});
								}
							}
						},{
							text: 'Reset',
							handler: function(){
								formPanel.getForm().reset();
							}
						}]
				});

			});
		
	</script>

</div>