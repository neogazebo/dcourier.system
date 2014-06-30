<?php

/**
 * Description of MenuAdministration
 *
 * @author febri
 */
class MenuAdministration
{

	public static function displayMenu()
	{
		return array(
			'cssFile' => '',
			'items' => array(
//				array('label' => 'File', 'url' => array(''),
//					'visible' => !Yii::app()->user->isGuest,
//					'items' => array(
//						array(
//							'label' => 'Update Profile',
//							'url' => array('/profile',
//							)
//						),
//						array(
//							'label' => 'Logout',
//							'url' => array('/site/logout'
//							)
//						),
//					),
//				),
				array('label' => 'Home', 'url' => array('/home'),
					'visible' => !Yii::app()->user->isGuest,
				),
				array('label' => 'Customer', 'url' => array('/customer/admin'),
					'visible' => !Yii::app()->user->isGuest,
					'items' => array(
						array(
							'label' => 'List Customer',
							'url' => array('/customer/admin',
							)
						),
						array(
							'label' => 'Add Customer',
							'url' => array('/customer/create'
							)
						),
					),
				),
				array('label' => 'Billing',
					'url' => array('/invoices/customerList'),
					'visible' => !Yii::app()->user->isGuest,
				),
				array('label' => 'Shipment',
					'url' => array('/shipment/customerService'),
					'visible' => !Yii::app()->user->isGuest,
					'items'=>array(
						array('label' => 'Cek Rate', 'url' => array('/shipment/cekRate')),
						array('label' => 'Create Order', 'url' => array('/shipment/createAWB')),
						array('label' => 'Tracing', 'url' => '#'),
					)
				),
				array('label' => 'Operation',
					'url' => array('/booking'),
					'visible' => !Yii::app()->user->isGuest,
					'items'=>array(
						array('label' => 'Courier', 'url' => array('/driver/index')),
//						array('label' => 'Pickup List', 'url' => array('/booking')),
						array('label' => 'Data Entry', 'url' => array('/shipment/createAWB')),
						array('label' => 'Bulk Data Entry','url'=>array('/shipment/entryBulkOrder')),
						array('label' => 'Bulk Update Status','url'=>array('/ordertracking/updateBulkStatus')),
					)
				),
				array('label' => 'Admin',
					'url' => '#',
					'visible' => !Yii::app()->user->isGuest,
					'items' => array(
						array('label' => 'Carrier',
							'url' => array('/rateCompany'),
							'visible' => !Yii::app()->user->isGuest,
						),
						array('label' => 'Product', 'url' => array('/product')),
					),
				),
				array('label' => 'Report',
					'url' => array('/report'),
					'visible' => !Yii::app()->user->isGuest,
				),
				array('label' => 'System', 'url' => '#', 'visible' => Yii::app()->user->checkAccess(Rights::module()->superuserName),
					'items' => array(
						array('label' => 'User', 'url' => array('/user/index'),
							'visible' => !Yii::app()->user->isGuest,
							'items' => array(
								array(
									'label' => 'List User',
									'url' => array('/user/index',
									)
								),
								array(
									'label' => 'Add User',
									'url' => array('/user/create'
									)
								),
							),
						),
//						array('label' => 'Tes Request PickUp Service', 'url' => array('/apitest/TesRequestPickup'), 'visible' => !Yii::app()->user->isGuest,),
						array('label' => 'RBAC Manager', 'url' => array('/rights'), 'visible' => Yii::app()->user->checkAccess(Rights::module()->superuserName)),
//						array('label' => 'Logs', 'url' => '#', 'items' => array(array('label' => 'User Logs', 'url' => array('/logs/user')),
//						)),
//						array('label' => 'Report', 'url' => array('/site/page', 'view' => 'report'), 'visible' => !Yii::app()->user->isGuest,),
					),
				),
			),
		);
	}
}
//	public static function displayMenu()
//	{
//		return array(
//			'cssFile' => '',
//			'items' => array(
//				array('label' => 'File', 'url' => array(''),
//					'visible' => !Yii::app()->user->isGuest,
//					'items' => array(
//						array(
//							'label' => 'Update Profile',
//							'url' => array('/profile',
//							)
//						),
//						array(
//							'label' => 'Logout',
//							'url' => array('/site/logout'
//							)
//						),
//					),
//				),
//				array('label' => 'Customer Management', 'url' => array('/customer/admin'),
//					'visible' => !Yii::app()->user->isGuest,
//					'items' => array(
//						array(
//							'label' => 'List Customer',
//							'url' => array('/customer/admin',
//							)
//						),
//						array(
//							'label' => 'Add Customer',
//							'url' => array('/customer/create'
//							)
//						),
////														array(
////															'label' => 'List Customer Type', 
////															'url' => array('#', 
////																)
////															),
////														array(
////															'label' => 'Add Customer Type', 
////															'url' => array('#'
////																)
////															),	
//					),
//				),
//				array('label' => 'Shipping Management', 'url' => '#',
//					'visible' => !Yii::app()->user->isGuest,
//					'items' => array(
//						array('label' => 'Domestic',
//							'url' => '#',
//							'visible' => !Yii::app()->user->isGuest,
//							'items' => array(
//								array('label' => 'Manage Area', 'url' => array('/province/admin', 'tag' => 'manageArea')),
//								array('label' => 'Manage Origins', 'url' => array('/origins/admin', 'tag' => 'manageArea')),
//								array('label' => 'Companies', 'url' => array('/RateCompany/admin', 'tag' => 'Rate Company')),
//								array('label' => 'Rate Price', 'url' => array('/RateDomestic/admin', 'tag' => 'Rate Price')),
//							),
//						),
//						array('label' => 'Intra City',
//							'url' => '#',
//							'visible' => !Yii::app()->user->isGuest,
//							'items' => array(
//								array('label' => 'Intra City Rates', 'url' => array('/IntraCityServices/admin', 'tag' => 'manageService')),
//								array('label' => 'Manage Area', 'url' => array('/IntraCityAreas/admin', 'tag' => 'manageArea')),
//								array('label' => 'Manage Service', 'url' => array('/IntraCityTypes/admin', 'tag' => 'manageType')),
//							),
//						),
//						array('label' => 'International',
//							'url' => '#',
//							'visible' => !Yii::app()->user->isGuest,
//							'items' => array(
//								array('label' => 'International Rate', 'url' => array('/international/')),
//								array('label' => 'Company', 'url' => array('/internationalCompany/admin')),
//								array('label' => 'Manage Country', 'url' => array('/zoneCountryService/admin'))
//							)
//						),
//						array('label' => 'Manage Good Type', 'url' => array('/GoodType/admin')),
//						array('label' => 'Courier', 'url' => array('/driver/admin')),
//					),
//				),
//				array('label' => 'Shipping Order',
//					'url' => '#',
//					'visible' => !Yii::app()->user->isGuest,
//					'items' => array(
//						array('label' => 'Manage Order Shipment', 'url' => array('/shipment/admin', 'tag' => 'create shipment')),
//						array('label' => 'Add New Domestic', 'url' => array('/shipment/create', 'service_type' => 'domestic')),
//						array('label' => 'Add New Intra City', 'url' => array('/shipment/create', 'service_type' => 'city')),
//						array('label' => 'Add New International', 'url' => array('/shipment/create', 'service_type' => 'international')),
//					),
//				),
//				array('label' => 'Tracking', 'url' => '#', 'visible' => !Yii::app()->user->isGuest,
//					'items' => array(
//						array('label' => 'Order Tracking', 'url' => array('/ordertracking/index')),
//						array('label' => 'Check AWB', 'url' => array('/shipment/checkawb')),
//					),
//				),
//				array('label' => 'Billing', 'url' => '#', 'visible' => !Yii::app()->user->isGuest,
//					'items' => array(
//						array('label' => 'Manage Billing', 'url' => '#'),
//						array('label' => 'Add New Billing', 'url' => '#'),
//					),
//				),
//				array('label' => 'System', 'url' => '#', 'visible' => !Yii::app()->user->isGuest,
//					'items' => array(
//						array('label' => 'User', 'url' => array('/user/index'),
//							'visible' => !Yii::app()->user->isGuest,
//							'items' => array(
//								array(
//									'label' => 'List User',
//									'url' => array('/user/index',
//									)
//								),
//								array(
//									'label' => 'Add User',
//									'url' => array('/user/create'
//									)
//								),
//							),
//						),
//						array('label' => 'Tes Request PickUp Service', 'url' => array('/apitest/TesRequestPickup'), 'visible' => !Yii::app()->user->isGuest,),
//						array('label' => 'RBAC Manager', 'url' => array('/rights'), 'visible' => Yii::app()->user->checkAccess(Rights::module()->superuserName)),
//						array('label' => 'Logs', 'url' => '#', 'items' => array(array('label' => 'User Logs', 'url' => array('/logs/user')),
//						)),
//						array('label' => 'Report', 'url' => array('/site/page', 'view' => 'report'), 'visible' => !Yii::app()->user->isGuest,),
//					),
//				),
//			),
//		);
//	}

?>
