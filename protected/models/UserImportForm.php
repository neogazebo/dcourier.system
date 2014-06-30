<?php

/**
 * Description of UserImportForm
 *
 * @author febri
 */
class UserImportForm extends CFormModel
{
	public $file;
	public $origin_id;
	public $service_id;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('file', 'file',
				'types' => 'csv',
				'maxSize' => 1024 * 1024 * 10, // 10MB
				'tooLarge' => 'The file was larger than 10MB. Please upload a smaller file.',
				'allowEmpty' => false
			),
			array('origin_id,service_id','required')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'file' => 'Select file',
			'origin_id' => 'Select Origin',
			'service_id' => 'Select Service',
		);
	}
}

?>
