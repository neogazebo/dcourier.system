<?php

return array(
		// application components
		'name' => 'D\'courier Management system',
		'components' => array(
				'db' => array(
						'connectionString' => 'mysql:host=localhost;dbname=dcourier',
						'emulatePrepare' => true,
						'username' => 'root',
						'password' => '414243',
						'charset' => 'utf8',
				),
				'messages' => array(
						'class' => 'CPhpMessageSource',
						'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '../messages',
				),
				'widgetFactory' => array(
//'class' => 'EWidgetFactory',
						'widgets' => array(
								'CJuiWidget' => array(
										'themeUrl' => 'system' . '/css/themes/',
										'theme' => 'gray_standard',
								),
				)),
				'cache' => array(
						'class' => 'system.caching.CFileCache',
				),
		),
		// application-level parameters that can be accessed
		// using Yii::app()->params['paramName']
		'params' => array(
				'uitheme' => 'gray_standard',
				'adminEmail' => 'webmaster@example.com',
		),
);