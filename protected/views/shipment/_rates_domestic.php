<?php 
	if($flag == 1):
?>
<h3>One kg price</h3>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'all-rates',
	'dataProvider' => $rates,
	'htmlOptions' => array('class' => 'hastable','style' => 'width:675px'),
	'summaryText' => '',
	'emptyText' => '<a href="javascript:;" onclick="$.fancybox.close();">Close</a>',
	'columns' => array(
		array(
			'name' => 'service',
			'type' => 'raw',
			'value' => function($data,$row){
				return $data->rateCompanyService->name;
			}
		),
		array(
			'name' => 'company',
			'type' => 'raw',
			'value' => function($data,$row){
				return $data->rateCompanyService->company->name;
			}
		),
		array(
			'name' => 'price',
			'type' => 'raw',
			'value' => function($data,$row){
				return 'Rp'.number_format($data->first_kg,2, ',' , '.');
			}
		),
		array(
			'name' => 'Transit Day',
			'type' => 'raw',
			'value' => function($data,$row){
				return $data->max_transit_time. ' days';
			}
		),
		array(
			'name' => '',
			'type' => 'raw',
			'value' => function($data,$row){
				return CHtml::link('use this', '#inquiry-detail', array('id' => 'domestic~'.$data->service_id.'~'.$data->rateCompanyService->name.'~'.$data->rateCompanyService->company->name.'~'.$data->max_transit_time.'~'.$data->id,'class' => 'use-this'));
			}
		)
	)
))
?>
<?php else :?>
<div class="o-box">
<p>Data Postcode tidak tersedia</p>
<p><a href="javascript:;" onclick="$.fancybox.close();">Close</a></p>
</div>
<?php endif; ?>
