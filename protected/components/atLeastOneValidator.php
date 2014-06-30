<?php

/**
 * Description of atLeastOneValidator
 *
 * @author Cahyo & Febri
 */
class atLeastOneValidator extends CValidator
{
	public $message = 'One of these {attributes} are required';

	public function validate($object, $attributes = null)
	{
		if (is_array($attributes))
			$attributes = array_intersect($this->attributes, $attributes);
		else
			$attributes = $this->attributes;
		$n = count($attributes);
		$errorCount = 0;
		$attributeLabel = array();
		foreach ($attributes as $attribute)
		{
			$attributeLabel[] = $object->getAttributeLabel($attribute);
			if ($this->isEmpty ($object->$attribute,TRUE))
			{
				$errorCount++;
			}
		}
		if ($n == $errorCount)
		{
			foreach ($attributes as $attribute)
				if (!$this->skipOnError || !$object->hasErrors($attribute)){
					$this->validateAttribute($object, $attribute);
					$this->clientValidateAttribute($object, $attribute,$attributes);
			}
		}
	}

	public function validateAttribute($object, $attribute)
	{
		$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} cannot be blank.'); 
		$this->addError($object,$attribute,$message); 
	}

	public function clientValidateAttribute($object, $attribute)
	{
		$attributes = $this->attributes;
		$className = get_class($object);
		$message = $this->message;
		if ($message === null)
			$message = Yii::t('yii', '{attribute} cannot be blank.');
		$message = strtr($message, array(
			'{attribute}' => $object->getAttributeLabel($attribute),
				));
		return "
if( $('#".$className."_$attributes[0]').val() == '' &&  $('#".$className."_$attributes[1]').val() == '' ){
messages.push(" . CJSON::encode($message) . ");
}
";
	}
}

?>
