<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="language" content="en" />
		
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ui/ui.base.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/themes/<?php echo Yii::app()->params['uitheme'] ?>/ui.css" />

		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/form.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css" />
	</head>

	<body>
		<div id="page_wrapper">
			<div id="page-header">
				<div id="page-header-wrapper">
					<div id="top">
						<a title="COSMIC" class="logo" href="#">COSMIC<br /> <sub>Customer Organizing System Management Information Center</sub> </a>
						<div class="welcome">
							<span class="note">Welcome, 
								<?php echo CHtml::link(Yii::app()->user->name, Yii::app()->controller->createUrl('/profile')); ?>
							</span>
							<a class="btn ui-state-default ui-corner-all" href="<?php echo $this->createUrl('/site/logout'); ?>">
								<span class="ui-icon ui-icon-power"></span>
								Logout
							</a>
						</div>
					</div>
					<div id="navigation">
						<?php
						$this->widget('application.extensions.CDropDownMenu.CDropDownMenu', MenuAdministration::displayMenu())

						?>
					</div>

				</div>
			</div>

				<?php if (isset($this->breadcrumbs)): ?>
				<div id="sub-nav">
					<?php
					$this->widget('zii.widgets.CBreadcrumbs', array(
						'links' => $this->breadcrumbs,
						'htmlOptions' => array('class' => 'page-title'),
					));

					?><!-- breadcrumbs -->
				</div>
<?php endif ?>

			<div class="clear"></div>

<?php echo $content; ?>

		</div>
		<div class="clear"></div>
		<div id="footer">
			<h1>CopyRight &copy; <a href="www.dcourier.com">www.d-courier.com</a></h1>
		</div>
	</body>
</html>