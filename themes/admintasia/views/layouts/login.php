<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="language" content="en" />

		<!-- blueprint CSS framework -->
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
		<!--[if lt IE 8]>
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
		<![endif]-->

		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ui/ui.base.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ui/ui.login.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/themes/<?php echo Yii::app()->params['uitheme'] ?>/ui.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/form.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css" />

		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	</head>

	<body>
		<div id="page_wrapper">
			<div id="page-header">
				<div id="page-header-wrapper">
					<div id="top">
						<a title="D'Courier Management System" class="logo" href="#">COSMIC Management System</a>
						<div class="welcome">

						</div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			<div id="page-layout">
				<div id="page-content">
					<div id="page-content-wrapper">
						<?php echo $content; ?>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		<div id="footer">
			<h1>CopyRight &copy; <a href="www.dcourier.com">www.dcourier.com</a></h1>
		</div>
	</body>
</html>