<?php

class ImportController extends Controller
{
	public function actionImportCustomer()
	{
		ini_set('max_execution_time', 300);
		$this->layout = '//layouts/column2';
		$model = new FAreaCodesCSV;
		
		$code_area = '';
		$city = '';
		$main_city_code = '';
		$location = '';

		if (isset($_POST['FAreaCodesCSV']))
		{

			$model->attributes = $_POST['FAreaCodesCSV'];

			if ($model->validate())
			{

				$csvFile = CUploadedFile::getInstance($model, 'file');
				$tempLoc = $csvFile->getTempName();
				$rawdatas = file($tempLoc);
				try
				{
					$connection = Yii::app()->db;
					$transaction = $connection->beginTransaction();
					$sql = "INSERT INTO area_code (code, city, main_city_code, location) VALUES(:code, :city, :main_city_code, :location)";
					$command = $connection->createCommand($sql);
					$command->bindParam(":code", $code_area);
					$command->bindParam(":city", $city);
					$command->bindParam(':main_city_code', $main_city_code);
					$command->bindParam(':location', $location);

					foreach ($rawdatas as $codes)
					{
						$code = explode(',', $codes);
						
						for($i = 0;$i < 4;$i++) $code[$i] = trim($code[$i]);
						
						$code_area = $code[0];
						$city = $code[1];
						$main_city_code = $code[2];
						$location = $code[3];

						$exec = $command->execute();
					}
					$transaction->commit();
				}
				catch (Exception $e) // an exception is raised if a query fails
				{
					CVarDumper::dump($e, 10, TRUE);
					exit;
					$transaction->rollBack();
				}
			}
		}

		$this->render("importcsv", array('model' => $model));
	}
}