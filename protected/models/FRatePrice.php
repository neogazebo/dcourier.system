<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FRatePrice
 *
 * @author cahyo
 */
class FRatePrice extends CFormModel
{
	public $companyService;
	public $origin;
	public $zone_id;
	public $district_id;
	public $to;
	public $mode;

	public function rules()
	{
		return array(
			array('companyService,origin,to', 'required'),
			array('zone_id,district_id,mode', 'safe'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'companyService' => Yii::t('web', 'Services'),
			'origin' => Yii::t('web', 'Origin'),
			'to' => Yii::t('web', 'Destination')
		);
	}
}

?>
