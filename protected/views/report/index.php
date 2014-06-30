<?php
$this->breadcrumbs = array(
	'Report',
);

$ajaxReport = CHtml::ajax(array(
			'dataType' => 'html',
			'url' => array('report/getReport'),
			'data' => 'js: {field:d,customer_id:customer_id,start : start,end:end} ',
			'success' => 'js:function(response){
		d = [];
		$("#gen_report").html(response);
	}'
		));

$script = <<<EOD
$(function() {
	$( "#sortable" ).sortable({
			placeholder: "ui-state-highlight"
	});
	$( "#sortable" ).disableSelection();
	
	$(".ui-state-default").live('dblclick',function(){
		$(this).addClass('selected');
	});
	$(".selected").live('dblclick',function(){
		$(this).removeClass('selected');
	});
	
	var d = [];
	$("#getReport").live('click',function(){
		$("#sortable li.selected").each(function(){
			d.push($(this).attr("id"));	
		});
		var customer_id = $("#customer_id").val();
		var start = $("#start").val();
		var end = $("#end").val();
		$ajaxReport
	})
});

EOD;
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerScript('formUserCreate', $script);

?>
<h4 class="ui-box-header ui-corner-all">Report</h4>

<div style="height: 200px;overflow-y: scroll;width: 220px;margin-top: 20px;float: left">
	<ul id="sortable">
		<?php foreach ($model->attributeLabels() as $key => $val): ?>
			<li class="ui-state-default" id="<?php echo $key ?>"><?php echo $val ?></li>
		<?php endforeach; ?>
	</ul>
</div>
<div style="float: left;margin-top: 20px;margin-left: 30px" class="wide form">
	<div class="row">
		<?php echo CHtml::label('Customer Account', 'customer_account') ?>
		<?php
		$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
			'name' => 'CustomerAccount',
			'htmlOptions' => array('size' => 40),
			'sourceUrl' => array('booking/suggestCustomer', 'mode' => 'accountnr'),
			'options' => array(
				'select' => 'js:function(event,ui){
													$("#customer_id").val(ui.item.id);
													return true;
												}',
				)
		));
		?>
<?php echo CHtml::hiddenField('customer_id','',array('id'=>'customer_id')) ?>
	</div>
	<div class="row">
		<?php echo CHtml::label('Start Date', 'start');
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'name' => 'start',
				'htmlOptions' => array(
					'id' => 'start'
				)
			));
		?>
	</div>
	<div class="row">
		<?php echo CHtml::label('End Date', 'end');
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'name' => 'end',
				'htmlOptions' => array(
					'id' => 'end'
				)
			));
		?>
	</div>
</div>
<div class="clearfix"></div>
<button class="l-btn" id="getReport">Generate</button>
<div id="gen_report"></div>