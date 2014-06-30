<?php
$this->breadcrumbs = array(
	'Profile',
);

?>
<h4 class="ui-box-header ui-corner-all"><?php echo Yii::t('web', 'Account Setting') ?></h4>
<?php
$script = <<<EOD
$('#tabs').tabs();
EOD;
$css = Yii::app()->assetManager->publish(Yii::getPathOfAlias('system.web.js.source.jui.css.base.jquery-ui') . '.css');
$cs = Yii::app()->clientScript;
$cs->registerCssFile($css);
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerScript('formUserCreate', $script);
$cs->registerCss('role', 'label[for=UserAssignmentForm_role]{
	margin-bottom:10px;
	}');
$this->menu = array(
	array('label' => 'Manage User', 'url' => array('index')),
);

?>
<div id="tabs">
	<ul>
		<li>
			<?php echo CHtml::link("General", '#tabs-1') ?>
		</li>
		<li>
			<?php echo CHtml::link("Access And Security", "#tabs-2") ?>
		</li>
	</ul>
	<div class="form">
		<?php
		if (Yii::app()->user->hasFlash('updateProfile')):

			?>
			<div class="success ui-tabs-panel">
				<?php echo Yii::app()->user->getFlash('updateProfile'); ?>
			</div>
		<?php endif; ?>
		<?php
		$form = new CForm(array(
					'activeForm' => array(
						'enableClientValidation' => 1,
						'htmlOptions' => array('enctype' => 'multipart/form-data'),
						'clientOptions' => array(
//							'validateOnSubmit' => 1
						),
					),
					'showErrorSummary' => 1,
					'elements' => array(
						'<div id="tabs-1">',
						'tabs-1' => array(
							'type' => 'form',
							'title' => 'General',
							'elements' => array(
								'firstname' => array(
									'type' => 'text'
								),
								'lastname' => array(
									'type' => 'text'
								),
								'email' => array(
									'type' => 'text'
								),
								'image'=>array(
									'type'=>'file',
								)
							)
						),
						'</div>',
						'<div id="tabs-2">',
						'tabs-2' => array(
							'type' => 'form',
							'title' => 'Akses dan Keamanan',
							'elements' => array(
								'username' => array(
									'type' => 'text',
									'disabled' => 'disabled',
								),
								'oldPassword' => array(
									'type' => 'password'
								),
								'newPassword' => array(
									'type' => 'password'
								),
								'confirmPassword' => array(
									'type' => 'password'
								),
								'</div>',
							),
							'buttons' => array(
								'<div class="ui-tabs-panel ui-widget-content ui-corner-bottom">',
								'Save' => array(
									'type' => 'submit',
									'label' => 'Save'
								),
							),
						),
					)
						), $model);
		echo $form->render();

		?>
	</div>
</div>
