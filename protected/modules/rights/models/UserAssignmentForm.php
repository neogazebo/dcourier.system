<?php
/**
- * Auth item assignment form class file.
- *
- * @author Christoffer Niska <cniska@live.com>
- * @copyright Copyright &copy; 2010 Christoffer Niska
- * @since 0.9
- */
class UserAssignmentForm extends CFormModel
{
	public $role;
	public $task;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('role,task', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'role' => 'Role',
		);
	}

	public function getAuthItemSelectOptions()
	{
		$authorizer = Yii::app()->getComponent('authorizer');
		return Rights::getAuthItemSelectOptions();
	}

	public function save($data = null, $id, $key)
	{
		$authorizer = Yii::app()->authManager;
		if ($this->validate())
		{
			$formModel = new AssignmentForm();
			if ($key == 'role')
				$formModel->itemname = $data;
			if ($formModel->validate() === true)
			{
				$authorizer->assign($formModel->itemname, $id);
				$item = $authorizer->getAuthItem($formModel->itemname);
				return true;
			}
			return false;
		}
		else
			return false;
	}

}