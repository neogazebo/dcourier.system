<?php

$this->widget('zii.widgets.jui.CJuiTabs', array(
		'cssFile' => array(''),
		'tabs' => array(
				'Login' => array('ajax' => Yii::app()->createUrl('/site/form_login'), 'id' => 'login'),
		),
		// additional javascript options for the tabs plugin
		'options' => array(
				'collapsible' => true,
		),
));
?>