<?php

/**
 * Description of FAreaCodes
 * This model is use for importing theree letter codes from csv
 *
 * @author febri
 */
class FOrderDataEntry extends CFormModel
{
	public $file;
	public $customer_account;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('file', 'file','types' => 'csv','maxSize' => 1024 * 1024 * 10,'tooLarge' => 'The file was larger than 10MB. Please upload a smaller file.','allowEmpty' => false,'on'=>'insert'),
			array('file', 'file','types' => null,'maxSize' => 1024 * 1024 * 10,'tooLarge' => 'The file was larger than 10MB. Please upload a smaller file.','allowEmpty' => false,'on'=>'api'),
			array('customer_account','required',),
			array('customer_account','length','max'=>255)
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'file' => 'Select file',
		);
	}
}

?>