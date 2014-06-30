<h3>Bellow 10 kg price</h3>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'all-rates',
	'dataProvider' => $rates,
	'htmlOptions' => array('class' => 'hastable','style' => 'width:675px'),
	'summaryText' => '',
	'enableSorting' => false,
	'emptyText' => '<a href="javascript:;" onclick="$.fancybox.close();">Close</a>',
	'columns' => array(
		array(
			'name' => 'service',
			'type' => 'raw',
			'value' => function($data,$row){
				return $data->service->name;
			}
		),
		array(
			'name' => 'company',
			'type' => 'raw',
			'value' => function($data,$row){
				return $data->service->company->name;
			}
		),
		array(
			'name' => 'price',
			'type' => 'raw',
			'value' => function($data,$row)use($zone){
				return 'Rp'.number_format($data->$zone,2, ',' , '.');
			}
		),
		array(
			'name' => 'Transit Day',
			'type' => 'raw',
			'value' => function($data,$row)use($country){
				return $country->transit_time. ' days';
			}
		),
		'weight',
		'type',
		array(
			'name' => '',
			'type' => 'raw',
			'value' => function($data,$row)use($country){
				return CHtml::link('use this', '#inquiry-detail', array('id' => 'international~'.$data->service_id.'~'.$data->service->name.'~'.$data->service->company->name.'~'.$country->transit_time.'~'.$data->type,'class' => 'use-this'));
			}
		)
	)
))
?>