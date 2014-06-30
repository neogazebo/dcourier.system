<?php
$this->breadcrumbs = array(
	'Order Tracking',
);
Yii::app()->trackOrder->registerAssignScript($model, 'tracking-grid', array(
	'ajaxUrl' => array('assignDriver')
));
Yii::app()->clientScript->registerCss('ui-datepicker-div', '#ui-datepicker-div{z-index:901!important}');
$this->widget('ext.commet.CComet', array(
	'success' => 'function(html){$grid=$(html).find("#response").html();$("#response").html($grid);
//taruh semua javascript tracking yang berhubungan dengan grid disini	
		jQuery("#assignDriver").dialog({"title":"Assign Driver","autoOpen":false,"modal":true,"buttons":{"Close":function(){$(this).dialog("close")},"Assign":function(){jQuery.ajax({"url":"/~febri/dcourier.system/index.php/ordertracking/assignDriver.html","type":"post","dataType":"json","data":$("#assignDriver form").serialize(),"success":function(r){if(r.success){
					$("#assignDriver").dialog("close");
					$.fn.yiiGridView.update("tracking-grid");
				}; 
				if(!r.success){alert("Gagal assign driver")}},"cache":false});;}}});
jQuery("body").undelegate(".assignDriver_button","click").delegate(".assignDriver_button","click",function(){
driverDialog=jQuery("#assignDriver");
driverDialog.dialog("open");
jQuery("#assignDriver input#OrderTracking_id").val($(this).attr("rel").replace("grid.",""));
	return false;
});	
jQuery("#OrderTracking_dateFrom").datepicker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional["id"], {"dateFormat":"d MM yy","changeMonth":true,"changeYear":true}));
jQuery("#OrderTracking_dateTo").datepicker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional["id"], {"dateFormat":"d MM yy","changeMonth":true,"changeYear":true}));
jQuery("#tracking-grid").yiiGridView({"ajaxUpdate":false,"ajaxVar":"ajax","pagerClass":"pager","loadingClass":"grid-view-loading","filterClass":"filters","tableClass":"items","selectableRows":1,"pageVar":"ViewTracking_page"});
		}',
	"timeOut" => 4000,
	"id" => "commet"
));

?>

<h4 class="ui-box-header ui-corner-all">Manage Order Trackings</h4>
<br />
<div class="search-form">
	<?php
	$this->renderPartial('_search', array(
		'model' => $model,
	));

	?>
</div><!-- search-form -->
<div id="response">
	<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'tracking-grid',
		'ajaxUpdate' => false,
		'filter' => $model,
		'dataProvider' => $model->search(),
		'columns' => array(
			array(
				'name' => 'order_id',
				'type' => 'raw',
				'value' => function($data,$row){
				return CHtml::link($data->order_id,array('TrackingDetails','awb' => $data->order_id));
				}
			),
			array(
				'name' => 'shipment_type',
				'value' => 'ucwords($data->shipment_type)',
				'filter' => CHtml::activeDropDownList($model, 'shipment_type', $model->shipmentTypeList, array('prompt' => 'Semua Type'))
			),
			'customer_name',
			array(
				'name' => 'driver',
				'value' => 'Yii::app()->trackOrder->getAssignButton($data,"driver")',
				'type' => 'raw',
				'filter' => CHtml::activeDropDownList($model, 'driver', $model->driverList, array('prompt' => 'Semua Driver')),
			),
			array(
				'name' => 'courier',
			),
			array(
				'name' => 'status',
				'value' => 'Yii::app()->trackOrder->getStatusLabel($data,"status",array("red"=>"red","yellow"=>"yellow","green"=>"green","createdTime"=>"created"))',
				'type' => 'raw',
				'filter' => CHtml::activeDropDownList($model, 'status', CHtml::listData(ShipmentStatus::model()->findAll(), 'status', 'status'), array('prompt' => 'Semua')),
			),
			array(
				'name' => 'event.title',
				'header' => 'Message',
			),
			array(
				'name' => 'Last Updated',
				'value' => 'Yii::app()->dateFormatter->formatDateTime($data->event_time,"long")',
				'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model' => $model,
					'attribute' => 'dateFrom',
					'language' => 'id',
					'options' => array(
						'dateFormat' => 'd MM yy',
						'changeMonth'=>true,
						'changeYear'=>true,
					)
						), true) . $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model' => $model,
					'attribute' => 'dateTo',
					'language' => 'id',
					'options' => array(
						'dateFormat' => 'd MM yy',
						'changeMonth'=>true,
						'changeYear' => true,
					)
						), true),
			)
		),
	));

	?>
</div>