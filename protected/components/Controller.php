<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends RController
{
	public $_authorizer;

	public function init()
	{
		$this->_authorizer = Yii::app()->getComponent('authorizer');
	}
	
	/**
	 * @return array action filters
	 */
//	public function filters()
//	{
//		return array(
//			'rights',
//		);
//	}
	
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = '//layouts/column1';

	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();

	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();

	protected function setJsonHeader()
	{
		session_cache_limiter('nocache');
		unset($_COOKIE);
		header('Content-type: application/json');
		header('Expires: ' . gmdate('r', 0));
	}
	
	public function beforeRender($view)
	{	
		$class=get_class($this);
		$action=$this->getAction()->getId();
		$cs = Yii::app()->clientScript;
		
		if($class==='ShipmentController' && $action==='createAWB' || $class==='ShipmentController' && $action==='updateAWB')
		{
			$cs->registerCssFile(Yii::app()->request->baseUrl.'/extjs/resources/css/ext-all.css');
			$cs->registerScriptFile(Yii::app()->request->baseUrl.'/extjs/ext-all.js');
			$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/ext.js');
			$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/resetuistyle.css');
		}
		else
		{
			$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/form-extended.css');
		}
		
		return parent::beforeRender($view);
	}
}