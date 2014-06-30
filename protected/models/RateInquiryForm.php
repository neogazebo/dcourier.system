<?php
/**
 * Description of RateInquiryForm
 *
 * @author febri
 */
class RateInquiryForm extends CFormModel
{
	public $shipping_charges;
	public $freight_charges;
	public $fuel_charges;
	public $vat;
	
	public function rules()
	{
		return array(
			array('shipping_charges, fuel_charges,freight_charges,vat','safe'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'freight_charges' => Yii::t('model', 'Delivery Charges'),
		);
	}
}

?>
