<?php

// uncomment the following to define a path alias
Yii::setPathOfAlias('rights', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'rights');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return CMap::mergeArray(
// Database configuration
				require(dirname(__FILE__) . '/common.php'), array(
			'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
			'theme' => 'admintasia',
			// preloading 'log' component
			'preload' => array('log'),
			// autoloading model and component classes
			'import' => array(
				'application.models.*',
				'application.components.*',
				'rights.*',
				'rights.models.*',
				'rights.components.*',
				'rights.components.behaviors.*',
				'rights.components.dataproviders.*',
				'ext.yii-mail.YiiMailMessage',
				'application.extensions.phpass.*',
			),
			'modules' => array(
// uncomment the following to enable the Gii tool
				'gii' => array(
					'class' => 'system.gii.GiiModule',
					'password' => 'dcourier',
					// If removed, Gii defaults to localhost only. Edit carefully to taste.
					'ipFilters' => array('127.0.0.1', '::1'),
				),
				// rbac configured to run with module Yii-User
				'rights' => array(
					'superuserName' => 'WebAdmin',
				),
				'tracking',
			),
			// session
// application components
			'components' => array(
				'hasher' => array(
					'class' => 'Phpass',
					'hashPortable' => false,
					'hashCostLog2' => 10,
				),
				'user' => array(
					'class' => 'RWebUser',
// enable cookie-based authentication
					'allowAutoLogin' => true,
				),
				'trackOrder' => array(
					'class' => 'ext.CTrackOrder'
				),
				'authorizer' => array(
					'class' => 'RAuthorizer',
				),
				'authManager' => array(
					'class' => 'RDbAuthManager', // Database driven Yii-Auth Manager
				),
				'session' => array(
					'class' => 'application.components.MyCDbHttpSession',
					'autoCreateSessionTable' => false,
					'connectionID' => 'db',
					'sessionTableName' => 'sessions',
					/**
					 * the configuration in 'php.ini' that must be set : 
					 * - session.cookie_lifetime
					 * - session.gc_maxlifetime
					 */
					'timeout' => 86400,
				),
				'widgetFactory' => array(
					'widgets' => array(
						'CJuiAccordion' => array(
							'cssFile' => false,
						),
						'CJuiAutoComplete' => array(
							'cssFile' => false,
						),
						'CJuiButton' => array(
							'cssFile' => false,
						),
						'CJuiDialog' => array(
							'cssFile' => false,
						),
						'CJuiDraggable' => array(
							'cssFile' => false,
						),
						'CJuiDroppable' => array(
							'cssFile' => false,
						),
						'CJuiInputWidget' => array(
							'cssFile' => false,
						),
						'CJuiProgressBar' => array(
							'cssFile' => false,
						),
						'CJuiResizable' => array(
							'cssFile' => false,
						),
						'CJuiSelectable' => array(
							'cssFile' => false,
						),
						'CJuiSlider' => array(
							'cssFile' => false,
						),
						'CJuiSliderInput' => array(
							'cssFile' => false,
						),
						'CJuiSortable' => array(
							'cssFile' => false,
						),
						'CJuiTabs' => array(
							'cssFile' => false,
						),
					),
				),
				'errorHandler' => array(
// use 'site/error' action to display errors
					'errorAction' => 'site/error',
				),
				'log' => array(
					'class' => 'CLogRouter',
					'routes' => array(
						array(
							'class' => 'CFileLogRoute',
							'levels' => 'error, warning',
						),
					// uncomment the following to show log messages on web pages
//						array(
//							'class' => 'CWebLogRoute',
//						),
					),
				),
				'ePdf' => array(
					'class' => 'ext.yii-pdf.EYiiPdf',
					'params' => array(
						'HTML2PDF' => array(
							'librarySourcePath' => 'application.vendors.html2pdf.*',
							'classFile' => 'html2pdf.class.php',
							'defaultParams' => array(
								'orientation' => 'P',
								'format' => 'A4',
								'language' => 'en',
							)
						)
					),
				),
				'mail' => array(
					'class' => 'ext.yii-mail.YiiMail',
					'transportType' => 'php',
					'viewPath' => 'application.views.mail',
					'logging' => true,
					'dryRun' => false
				),
			),
			// Default params
			'params' => array(
				// Vendors stuff
				'vendors' => require(dirname(__FILE__).'/vendors.php')
			) // params
				)
);