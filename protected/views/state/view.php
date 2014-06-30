<?php

$this->breadcrumbs = array(
		'States' => array('admin'),
		$model->name,
);

$this->menu = array(
		array('label' => 'Manage State', 'url' => array('admin')),
		array('label' => 'Create Zone', 'url' => Yii::app()->createUrl('zone/create', array('id' => $model->id))),
);
?>



<?php

$this->widget('zii.widgets.CDetailView', array(
		'data' => $model,
		'attributes' => array(
				'id',
				'name',
		),
));
?>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'zone-grid',
		'htmlOptions'=>array('class'=>'hastable'),
		'dataProvider' => $zone,
		'columns' => array(
				'id',
				'name',
				array(
						'class' => 'CButtonColumn',
						'template' => '{update}{delete}',
						'buttons' => array(
								'update' => array(
										'url' => 'Yii::app()->createUrl("zone/update",array("id"=>$data->id))',
										
								),
								'delete' => array(
										'url' => 'Yii::app()->createUrl("zone/delete",array("id"=>$data->id))',
										
								),
						),
				),
		),
));
?>
