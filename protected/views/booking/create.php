<?php
$this->breadcrumbs=array(
	'Bookings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Booking', 'url'=>array('index')),
);
?>

<h1>Create Booking</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'awb'=>$awb)); ?>