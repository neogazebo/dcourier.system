<?php

function DMStoDEC($dms, $longlat)
{

	if ($longlat == 'lattitude')
	{
		$spl = explode('.', $dms);
		if (strlen($spl[0]) == 3)
			$dms = '0' . $dms;
		$deg = substr($dms, 0, 2);
		$min = substr($dms, 2, 8);
		$sec = '';
	}
	if ($longlat == 'longitude')
	{
		$deg = substr($dms, 0, 3);
		$min = substr($dms, 3, 8);
		$sec = '';
	}

	return $deg + ((($min * 60) + ($sec)) / 3600);
}
if (isset($_GET['gprmc']))
{

// configuration
	$dbhost = "localhost";
	$dbname = "dcourier";
	$dbuser = "dermawan";
	$dbpass = "bandoeng";

// database connection
	$conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "select * from driver where user_id=:id";
	$sth = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$sth->execute(array(':id' => $_GET['id']));
	$rows = $sth->fetchAll();
	$numRow = count($rows);

	if ($numRow == 0)
		error_log('skip user id not exist');
	else
	{
		$gps = $_GET['gprmc'];

		$gprmc = explode(',', $gps);
		$data['timestamp'] = strtotime('now');
		$data['sat_status'] = $gprmc[2];

		$data['lattitude_dms'] = $gprmc[3];
		$data['lattitude_decimal'] = '-' . DMStoDEC($gprmc[3], 'lattitude');
		$data['lattitude_direction'] = $gprmc[4];

		$data['longitude_dms'] = $gprmc[5];
		$data['longitude_decimal'] = DMStoDEC($gprmc[5], 'longitude');
		$data['longitude_direction'] = $gprmc[6];

		$data['speed_knots'] = $gprmc[7];

		$data['bearing'] = $gprmc[8];

		$data['google_map'] = 'http://maps.google.com/maps?q=' . $data['lattitude_decimal'] . ',' . $data['longitude_decimal'] . '+(PHP Decoded)&iwloc=A';

// query
		$sql = "INSERT INTO driver_location VALUES ('',:user_id,:time,:lat,:long)";
		$q = $conn->prepare($sql);
		$q->bindParam(':user_id', $_GET['id']);
		$q->bindParam(':time', $data['timestamp']);
		$q->bindParam(':lat', $data['lattitude_decimal']);
		$q->bindParam(':long', $data['longitude_decimal']);
		$q->execute();

		error_log('set new location user ' . $_GET['id']);
	}
}






