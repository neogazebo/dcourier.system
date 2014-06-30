<?php
$this->breadcrumbs=array(
	'Invoices',
);

$this->menu=array(
	array('label'=>'Create Invoices', 'url'=>array('create','id'=>$customer->id)),
	array('label'=>$customer->name.' Summary', 'url'=>array('customer/view','id'=>$customer->id)),
);
?>

<h4 class="ui-box-header ui-corner-all">Invoices for <?php echo $customer->name ?></h4>
<br />
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'customer-invoice-grid',
	'dataProvider'=>$dataProvider,
	'htmlOptions'=>array('class'=>'hastable'),	
	'columns'=>array(
		'id',
		array(
			'name' => 'tgl_terbit',
			'type' => 'raw',
			'value' => function($data,$row) {
				$formatted_date = Yii::app()->dateFormatter->formatDateTime($data->tgl_terbit,'long',null);
				return CHtml::link($formatted_date, Yii::app()->createUrl('invoices/view',array('id' => $data->id)));
			}
		),
		array(
			'name'=>'tgl_pembayaran',
			'type' => 'raw',
			'value' => function($data,$row){
				if($data->tgl_pembayaran != '')
					return Yii::app()->dateFormatter->formatDateTime(strtotime($data->tgl_pembayaran),'long',null);
			}
		),
		'tgl_jatuh_tempo',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{delete}{update}'
		),
	),
));  ?>