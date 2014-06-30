<?php

class UpdatedbCommand extends CConsoleCommand
{
	const NomorNetKodePosBaseUrl = 'http://kodepos.nomor.net/';
	const NomorNetKodePosProvinsiUrl = 'http://kodepos.nomor.net/_kodepos.php?_i=provinsi-kodepos&sby=010000';
	const MaxRetry = 10;
	const timeOutMs = 300;

	public function __construct()
	{
	}

	public function initContent($url)
	{

		$headers[] = 'Connection: Keep-Alive';
		$headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
		$user_agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)';
		$process = curl_init($url);
		curl_setopt($process,CURLOPT_HTTPHEADER,$headers);
		curl_setopt($process,CURLOPT_HEADER,0);
		curl_setopt($process,CURLOPT_USERAGENT,$user_agent);
		curl_setopt($process,CURLOPT_TIMEOUT,30);
		curl_setopt($process,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($process,CURLOPT_FOLLOWLOCATION,1);
		$return = curl_exec($process);
		curl_close($process);
		return $return;
	}

	public function getContents($url)
	{
		$data = self::initContent($url);
		RETURN $data;
	}

	public function getRows($pq)
	{

		$rows = $pq->find("table[bgcolor='#ffccff'] tr[bgcolor='#ccffff']");
		$n = count($rows);
		echo 'Jumlah propinsi: ' . $n . " \n";
		$count = 0;
		foreach($rows as $value)
		{
			$count++;
			$row = pq($value);
			$kolom_provinsi = $row->find('td:eq(1) > a');
			$nama_provinsi = $kolom_provinsi->text();
			$link_provinsi = str_replace(' ','%20',self::NomorNetKodePosBaseUrl . $kolom_provinsi->attr('href')) . '&perhal=1000';
			echo sprintf("Memproses propinsi %d dari %d\n",$count,$n);
			echo sprintf("Propinsi: %s Link: %s\n",$nama_provinsi,$link_provinsi);

			$province = Province::model()->findByAttributes(array('name'=>$nama_provinsi));
			if(!($province instanceof Province)) {
				$province = new Province;
				$province->name = $nama_provinsi;
				if(!$province->save())
					throw new CException('Cannot save province');
			}

			$countdistrict = 0;
			$pqDist = phpQuery::newDocumentHtml($this->getContents($link_provinsi));
			$rowsDist = $pqDist->find("table[bgcolor='#ffccff'] tr[bgcolor='#ccffff']");
			$nDist = count($rowsDist);
			$countDist = 0;
			foreach($rowsDist as $valueDist)
			{
				$countDist++;
				$rowDist = pq($valueDist);
				switch($rowDist->find('td:eq(2)')->text())
				{
					case 'Kota':
						$tipeDist = 'kota';
						break;
					default:
					case 'Kab.':
						$tipeDist = 'kabupaten';
						break;
				}
				$kolomDist = $rowDist->find('td:eq(3) > a');
				$namaDist = $kolomDist->text();
				$linkDist = str_replace(' ','%20',self::NomorNetKodePosBaseUrl . $kolomDist->attr('href')) . '&perhal=1000';
				echo sprintf("Memproses distrik " . $province->name . " %d dari %d\n",$countDist,$nDist);
				echo sprintf("Distrik: %s Link: %s\n",$namaDist,$linkDist);

				$distrik = District::model()->findByAttributes(array('name'=>$namaDist,'province_id'=>$province->id,'type'=>$tipeDist));
				if(!($distrik instanceof District))
				{
					$distrik = new District;
					$distrik->name = $namaDist;
					$distrik->type = $tipeDist;
					$distrik->province_id = $province->id;
					if (!$distrik->save())
						throw new CException('Cannot save district');
				}

					$pqZone = phpQuery::newDocumentHtml($this->getContents($linkDist));
					$rowsZone = $pqZone->find("table[bgcolor='#ffccff'] tr[bgcolor='#ccffff']");
					$nZone = count($rowsZone);
					$countZone = 0;
					foreach($rowsZone as $valueZone)
					{
						$countZone++;
						$rowZone = pq($valueZone);
						$kolomZone = $rowZone->find('td:eq(4) > a');
						$namaZone = $kolomZone->text();
						$linkZone = str_replace(' ','%20',self::NomorNetKodePosBaseUrl . $kolomZone->attr('href')) . '&perhal=1000';
						echo sprintf("Memproses zone  %d dari %d\n",$countZone,$nZone);
						echo sprintf("zone: %s Link: %s\n",$namaZone,$linkZone);
						$new_zone = false;
						$zone = Zone::model()->findByAttributes(array('name'=>$namaZone,'district_id'=>$distrik->id));
						if(!($zone instanceof Zone))
						{
							$zone = new Zone;
							$zone->name = $namaZone;
							$zone->active = 1;
							$zone->district_id = $distrik->id;
							if(!$zone->save())
								throw new CException('Cannot save Zone');
							$new_zone = true;
						}

							echo 'sukses saving zone' . "\n";
							$countArea = 0;
							$pqArea = phpQuery::newDocumentHtml($this->getContents($linkZone));
							$rowsArea = $pqArea->find("table[bgcolor='#ffccff'] tr[bgcolor='#ccffff']");
							$nArea = count($rowsArea);
							// Let's speed up things a bit
							$trans=Yii::app()->db->beginTransaction();
							foreach($rowsArea as $valueArea)
							{
								$countArea++;
								$rowArea = pq($valueArea);
								$kolomArea = $rowArea->find('td:eq(2) > a');
								$kolomKodePos = $rowArea->find('td:eq(1)');
								$namaArea = $kolomArea->text();
								$kodePos = $kolomKodePos->text();
								$linkKodePos = str_replace(' ','%20',self::NomorNetKodePosBaseUrl . $kolomArea->attr('href')) . '&perhal=1000';
								$area = $new_zone
									? null
									: Area::model()->findByAttributes(array('name'=>$namaArea,'zone_id'=>$zone->id));
								if(!($area instanceof Area))
								{
									$area = new Area;
									$area->name = $namaArea;
									$area->postcode = $kodePos;
									$area->zone_id = $zone->id;
									if(!($area->save()))
										throw new CException('Cannot save area');
								}

								echo 'Sukses Saving Code Post' . "/n kode pos adalah " . $area->postcode."\n";
							}
							$trans->commit();
					}
			}
		}
	}

	public function actionIndex()
	{
		echo 'Loading.....';
		$get = new self;
		$content = $get->getContents(self::NomorNetKodePosBaseUrl);
		$pq = phpQuery::newDocumentHTML($content);
		echo "\n";
		$get->getRows($pq);
	}
}
