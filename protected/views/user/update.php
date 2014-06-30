<?php
$this->breadcrumbs = array(
	'Users' => array('index'),
	'Update '. ucfirst($model->username)
,
);
$script = <<<EOD
$('#tabs').tabs();
EOD;
$css = Yii::app()->assetManager->publish(Yii::getPathOfAlias('system.web.js.source.jui.css.base.jquery-ui') . '.css');
$cs = Yii::app()->clientScript;
$cs->registerCssFile($css);
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerScript('formUserCreate', $script);
$cs->registerCss('role','label[for=UserAssignmentForm_role]{
	margin-bottom:10px;
	}');
$this->menu = array(
	array('label' => 'Manage User', 'url' => array('index')),
);

?>

<h4 class="ui-box-header ui-corner-all">
	Create User
</h4>
<div id="tabs">
	<ul>
		<li>
			<?php echo CHtml::link("General", '#tabs-1') ?>
		</li>
		<li>
			<?php echo CHtml::link("Akses dan Keamanan", "#tabs-2") ?>
		</li>
		<li>
			<?php echo CHtml::link('Perizinan', '#tabs-3') ?>
		</li>
	</ul>
	<div class="form">
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
								'telp_home' => array(
									'type' => 'text'
								),
								'telp_office' => array(
									'type' => 'text',
								),
								'timezone' => array(
									'type' => 'dropdownlist',
									'items' => $model->listTimeZone(),
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
									'disabled'=>'disabled',
								),
//								'oldPassword' => array(
//									'type' => 'password'
//								),
								'newPassword' => array(
									'type' => 'password'
								),
								'confirmPassword' => array(
									'type' => 'password'
								),
							),
						),
						'</div>',
						'<div id="tabs-3">',
						'tabs-3' => array(
							'type' => 'form',
							'model'=>$permissionModel,
							'title' => 'Akses dan Keamanan',
							'class'=>'test',
							'elements' => array(
								'role' => array(
									'type' => 'checkboxlist',
									'items'=>$permissionModel->authItemSelectOptions['Roles'],
									'template'=>'{input}{label}',
									'style'=>'float:left;margin-right:10px;',
								),
//								'task' => array(
//									'type' => 'checkboxlist',
//									'items'=>$permissionModel->authItemSelectOptions['Tasks'],
//									'prompt'=>'- Pilih Task -',
//									'template'=>'{input}{label}',
//									'style'=>'float:left;margin-right:10px;',
//								),
								'<div style="clear:both"></div>',
								'</div>',
							),
							'buttons' => array(
								'<div class="ui-tabs-panel ui-widget-content ui-corner-bottom">',
								'Save' => array(
									'type' => 'submit'
								),
								'</div>'
							),
						),
					)
						), $model);
		echo $form->render();

		?>
	</div>
</div>
