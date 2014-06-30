<?php
$this->breadcrumbs = array(
		'Users' => array('index'),
		 ucfirst($model->username),
);

$this->menu = array(
		array('label' => 'List User', 'url' => array('index')),
		array('label' => 'Create User', 'url' => array('create')),
		array('label' => 'Update User', 'url' => array('update', 'id' => $model->id)),
		array('label' => 'Delete User', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?'))
);
?>

<h1>Detail User <?php echo $model->id; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
		'data' => $model,
		'attributes' => array(
				'id',
				'username',
				'email',
				'firstname',
        'lastname',
        'nip',
        'telp_home',
        'timezone',
        'telp_office',
				array(
						'label' => yii::t('application', 'Created'),
						'type' => 'raw',
						'value' => Yii::app()->getDateFormatter()->formatDateTime($model->created),
				),
				array(
						'label' => yii::t('application', 'Updated'),
						'type' => 'raw',
						'value' => Yii::app()->getDateFormatter()->formatDateTime($model->updated),
				),
				array(
						'label' => yii::t('application', 'Access'),
						'type' => 'raw',
						'value' => Yii::app()->getDateFormatter()->formatDateTime($model->access),
				),
				array(
						'label' => yii::t('application', 'Active'),
						'type' => 'raw',
						'value' => $model->getActive(),
				),
		),
));
?>
