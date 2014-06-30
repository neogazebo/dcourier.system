<?php
	$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'cust-token',
	'dataProvider' => $shipment_report,
	'ajaxUpdate' => true,
	'htmlOptions' => array('class' => 'hastable'),
	'columns' => $columns
));
?>

<?php echo CHtml::link('Export to Excel', yii::app()->createUrl('report/exportExcel',array('fields'=>$columns)),array('class'=>'l-btn')); ?>