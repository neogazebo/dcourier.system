<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return CMap::mergeArray(
	// Database configuration
	require(dirname(__FILE__) . '/common.php'), array(
		'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
		'name'=>'DCourier Console App',
		'import' => array(
          'application.components.*',
					'application.models.*',
		),
		'commandMap' => array(
			'webRoot' => realpath('./../../'),
		),
		// Default params
		'params' => array(
			// Vendors stuff
			'vendors' => require(dirname(__FILE__).'/vendors.php')
		) // params
	)
);