<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CUpdatePasswordValidator
 *
 * @author cahyo
 */
class CUpdatePasswordValidator extends CValidator{
		public $newPasswordAttribute='newPassword';
		public $savedPasswordAttribute='password';
		public $message='{attribute} lama yang anda masukan salah';
		
		protected function validateAttribute($object, $attribute)
		{
				$newPasswordAttribute=$this->newPasswordAttribute;
				if(!$object->hasErrors($attribute)){
						if($object->$attribute!=null || $object->$newPasswordAttribute){
								$this->compareWithSavedPassword($object,$attribute);
								if($object->$newPasswordAttribute=='')
								{
										$this->addError($object, $newPasswordAttribute, '{newPasswordAttribute} tidak boleh kosong',array('{newPasswordAttribute}'=>$object->getAttributeLabel($newPasswordAttribute)));
								}
						}
				}
		}
		
		protected function compareWithSavedPassword($object, $attribute)
		{
				if(!$object->validatePassword($object->$attribute))
						$this->addError ($object, $attribute, $this->message,array('{attribute}'=>$object->getAttributeLabel($attribute)));
		}
		
}

?>
