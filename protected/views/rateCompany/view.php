<?php
$this->breadcrumbs = array(
		'Perusahaan Penyedia Jasa'=>array('admin'),
		$model->name,
);

$this->menu = array(
		array('label' => 'Manage Company', 'url' => array('admin')),
		array('label' => 'Buat Layanan Baru', 'url' => Yii::app()->createUrl('RateCompanyService/create', array('id' => $model->id))),
);
?>

<h4 class="ui-box-header ui-corner-all">
	Perusahaan : <?php echo $model->name; ?>
</h4>
<p></p>
<h1>Data Layanan</h1>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'rate-company-service-grid',
		'ajaxUpdate' => false,
		'dataProvider' => $service,
		'htmlOptions'=>array('class'=>'hastable'),
		'columns' => array(
				'id',
				'name',
				array(
						'class' => 'CButtonColumn',
						'template' => '{update}{delete}',
						'buttons' => array(
								'update' => array(
										'url' => 'Yii::app()->createUrl("RateCompanyService/update",array("id"=>$data->id, "companyID"=>$data->company_id))',
								),
								'delete' => array(
										'url' => 'Yii::app()->createUrl("RateCompanyService/delete",array("id"=>$data->id))',
								),
						),
				),
		),
));
?>
