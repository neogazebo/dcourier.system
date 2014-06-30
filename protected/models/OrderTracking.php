<?php

/**
 * This is the model class for table "order_tracking".
 *
 * The followings are the available columns in table 'order_tracking':
 * @property string $id
 * @property integer $shipments_id
 * @property integer $driver_id
 *
 * The followings are the available model relations:
 * @property Users $driver
 * @property Shipments $shipments
 */
class OrderTracking extends CActiveRecord
{
	CONST SQL = '
SELECT shipment.id, shipment.awb as order_id, shipment.service_type as
shipment_type, cust.name as customer_name, cust.accountnr, 
status.status, status.green, status.yellow, status.red, status.based_date, 
concat(driver.firstname," ",driver.lastname) as driver, null as courier,
shipment.created, shipment.modified,shipment.event_time
FROM `order_tracking` `ot` 
LEFT JOIN shipments shipment on ot.shipments_id=shipment.id 
LEFT JOIN shipment_status status ON shipment.shipping_status=status.id 
LEFT JOIN users driver ON driver_id=driver.id
LEFT JOIN customers cust ON shipment.customer_id=cust.id
WHERE shipment.service_type="city"

UNION

SELECT shipment.id, shipment.awb as order_id, shipment.service_type as
shipment_type,  cust.name as customer_name, cust.accountnr, 
status.status, status.green, status.yellow, status.red, status.based_date,concat(driver.firstname," ",driver.lastname) as
driver,concat(rc.name," - ",rcs.name) as courier,
shipment.created, shipment.modified, shipment.event_time
FROM `order_tracking` `ot` 
LEFT JOIN shipments shipment on ot.shipments_id=shipment.id 
LEFT JOIN shipment_status status ON shipment.shipping_status=status.id 
LEFT JOIN users as driver ON driver_id=driver.id 
LEFT JOIN customers cust ON shipment.customer_id=cust.id
LEFT JOIN rate_price rp ON shipment.service_id=rp.id 
LEFT JOIN rate_company_service rcs ON rcs.id=rp.service_id 
LEFT JOIN rate_company rc ON company_id=rc.id 
WHERE shipment.service_type="domestic"

UNION

SELECT shipment.id, shipment.awb as order_id, shipment.service_type as
shipment_type,  cust.name as customer_name, cust.accountnr,  
status.status, status.green, status.yellow, status.red, status.based_date,concat(driver.firstname," ",driver.lastname) as
driver, concat(ic.name," - ",ics.name) as courier,
shipment.created, shipment.modified, shipment.event_time
FROM `order_tracking` `ot` 
LEFT JOIN shipments shipment on ot.shipments_id=shipment.id 
LEFT JOIN shipment_status status ON shipment.shipping_status=status.id 
LEFT JOIN customers cust ON shipment.customer_id=cust.id
LEFT JOIN users driver ON driver_id=driver.id 
LEFT JOIN international_company_service ics ON shipment.service_id = ics.id
LEFT JOIN international_company ic ON ics.company_id = ic.id
WHERE shipment.service_type="international"
';

	public $pageSize = 20;
	public $status;
	public $courier;
	public $shipment_type;
	public $customer_name;
	public $accountnr;
	public $order_id;
	public $created;
	public $message;
	public $dateFrom;
	public $dateTo;

	/**
	 * Returns the static model of the specified AR class.
	 * @return OrderTracking the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order_tracking';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shipments_id', 'required'),
			array('shipments_id, driver_id,', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, dateFrom, dateTo,order_id,customer_name,accountnr,shipment_type, pageSize,driver, status, courier', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'driver' => array(self::BELONGS_TO, 'User', 'driver_id'),
			'shipment' => array(self::BELONGS_TO, 'Shipment', 'shipments_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'shipments_id' => 'Shipments',
			'driver_id' => 'Driver',
		);
	}

	/**
	 * @property CDateTimeParser $timeParser 
	 * @return \CActiveDataProvider 
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
		
		if (!empty($this->dateFrom) && !empty($this->dateTo))
		{
			$month = array(
				'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'Nopember',
				'Desember'
			);
			$monthNum = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
			$timeFrom = str_replace($month, $monthNum, $this->dateFrom);
			$timeTo = str_replace($month, $monthNum, $this->dateTo);
			$hms=Yii::app()->trackOrder->eplasedTimeTill24;
			$timeParser = new CDateTimeParser();
			$timeFrom = $timeParser->parse($timeFrom, 'd MM yyyy');
			$timeTo = $timeParser->parse($timeTo." ".$hms, 'd MM yyyy h:m:s');

			$criteria->addBetweenCondition('created', $timeFrom, $timeTo);
		}
		$criteria->addSearchCondition('order_id', "{$this->order_id}%", FALSE, 'AND', 'LIKE');
		$criteria->compare('shipment_type', $this->shipment_type);
		$criteria->compare('customer_name', $this->customer_name, true);
		$criteria->compare('driver', $this->driver);
		$criteria->compare('courier', $this->courier);
		$criteria->compare('status', $this->status, false);
		return new CActiveDataProvider('ViewTracking', array(
					'criteria' => $criteria,
					'pagination' => array(
						'pageSize' => $this->pageSize
					),
				));
	}

	public static function getShipmentTypeList()
	{
		return array(
			'city' => 'City',
			'domestic' => 'Domestic',
			'international' => 'International'
		);
	}

	public static function getDriverList()
	{
		$return = array();
		$user = Yii::app()->db->createCommand(<<<EOD
 SELECT user.id,
	 concat(user.firstname," ",user.lastname) as drivername
 FROM users as user left join AuthAssignment as auth on user.id=auth.userid
	  where auth.itemname = 'driver'
EOD
				)->queryAll();
		foreach ($user as $value)
		{
			$return[$value['drivername']] = $value['drivername'];
		}

		return $return;
	}
}