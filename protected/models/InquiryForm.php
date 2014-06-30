<?php
/**
 * Description of InquiryForm
 *
 * @author febri
 */
class InquiryForm extends CFormModel
{
	public $customer_account;
	public $shipper_city;
	public $shipper_city_code;
	public $shipper_country;
	public $shipper_country_code;
	public $shipper_postal;
	public $receiver_city;
	public $receiver_city_code;
	public $receiver_zone_code;
	public $receiver_country;
	public $receiver_country_code;
	public $receiver_country_id;
	public $receiver_postal;
	public $commodity;
	public $piece;
	public $insurance;
	public $special_service;
	public $pickup_date;
	public $delivery_date;
	public $courier;
	public $transit_time;
	public $intra_city_area;
	public $data_rate;
	public $service_name;
	public $shipping_charges;
	public $freight_charges;
	public $fuel_charges;
	public $vat;
	public $domestic_ratePrice_id;
	public $package_weight;
	public $cod;
	public $service_code;
	public $listspecialservice = array('saturday delivery' => 'Saturday Delivery', 'remote area' => 'Remote Area');
	
	public function rules()
	{
		return array(
			array('receiver_postal,service_code','required','on'=>'api-rate-grocery'),
			array('customer_account, shipper_city_code, shipper_country_code, receiver_city_code, receiver_country_code, receiver_postal,commodity, piece, insurance, special_service, pickup_date, delivery_date, courier, transit_time,receiver_zone_code,intra_city_area,receiver_country_id,data_rate,shipping_charges, fuel_charges,freight_charges,vat,domestic_ratePrice_id,cod,service_code','safe'),
			array('shipper_postal,receiver_postal', 'length', 'min' => 5, 'max' => 5),
			array('receiver_city', 'length', 'min' => 4, 'max' => 50),
			array('receiver_city, receiver_postal','atLeastOneValidator'),
			array('receiver_country,shipper_country,shipper_city,package_weight,receiver_city, receiver_postal','required','on' => 'api-rate'),
		);
	}
	
	public function domestic_service_type()
	{
		return array(
			'domestic' => 'Domestic',
			'city' => 'Intra City',
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'receiver_country' => 'Receiver Country'
		);
	}
	
}

?>
