<?php

class RateDomesticController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column1';

	/**
	 * @return array action filters
	 */
	public function beforeAction($action)
	{
		return parent::beforeAction($action);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function actionIndex()
	{
		$this->redirect(array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin($oid = '', $csid = '', $zid = '', $did = '', $mode = '')
	{
		//validate that csid is true at the active record
		if ($csid != '')
			$csid = $this->loadCSModel($csid)->primaryKey;

		//validate that oid is true at the active record
		if ($oid != '')
			$oid = $this->loadOModel($oid)->primaryKey;
		$model = new FRatePrice;
		$model->companyService = $csid;
		$model->origin = $oid;

		if ($mode == 'district')
			$districts = District::getDistrictRatePrice($did);

		$zones = Zone::getZoneRatePrice($zid, $did, $mode);

		$data_render = array(
			'model' => $model,
			'zones' => $zones,
			'csid' => $csid,
			'oid' => $oid,
			'zid' => $zid,
			'did' => $did,
			'mode' => $mode
		);

		if ($mode == 'district')
			$data_render['district'] = $districts;

		$this->render('admin', $data_render);
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'rate-price-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	protected function getthrown()
	{
		throw new CHttpException('404', 'Halaman tidak ditemukan');
	}

	protected function loadCSModel($id)
	{
		$model = RateCompanyService::model()->findByPk($id);
		if (!$model)
			$this->thrown;
		return $model;
	}

	protected function loadOModel($id)
	{
		$model = Origins::model()->findByPk($id);
		if (!$model)
			$this->thrown;
		return $model;
	}

	//begin ajax url here.....
	/**
	 * performs submit layanan & jasa dan origins submit , validate the form and render 
	 * @param RateDomestic Gridview 
	 */
	public function actionsubmitOrigin()
	{
		$model = new FRatePrice();
		if (isset($_POST['FRatePrice']))
		{
			$model->attributes = $_POST['FRatePrice'];
			if ($model->validate())
			{
				echo CJSON::encode(array('success' => true, 'message' => $this->createUrl('admin', array('oid' => $model->origin, 'csid' => $model->companyService, 'zid' => $model->zone_id, 'did' => $model->district_id, 'mode' => $model->mode))));
				Yii::app()->end();
			}
			else
			{
				$errorMessage = array();
				foreach ($model->getErrors() as $key => $errors)
				{
					foreach ($errors as $error)
					{
						$errorMessage[CHtml::activeId($model, $key)] = $error;
					}
				}
				echo CJSON::encode(array('success' => false, 'message' => $errorMessage));
				Yii::app()->end();
			}
		}
	}

	public function actionValidateRatePrice()
	{
		if (!Yii::app()->request->isAjaxRequest)
			$this->thrown;
		$attributes = array();
		$model = new RateDomestic;
		if (isset($_POST['RateDomestic']))
		{
			foreach ($_POST['RateDomestic'] as $key => $value)
			{
				foreach ($value as $k => $v)
				{
					$attributes[$k] = $v;
				}
			}
			if ($attributes != array())
			{
				$model->attributes = $attributes;
				$model->service_id = $_POST['service_id'];
				$model->origin_id = $_POST['service_id'];

				if ($model->validate())
				{
					echo CJSON::encode(array('success' => true, 'classid' => $key, 'message' => 'sukses'));
					Yii::app()->end();
				}
				else
				{
					$errorMessage = array();
					foreach ($model->getErrors() as $errorKey => $errors)
					{
						foreach ($errors as $error)
						{
							$errorMessage['RatePrice_' . $errorKey . '_em'] = $error;
						}
					}
					echo CJSON::encode(array('success' => false, 'classid' => $key, 'message' => $errorMessage));
					Yii::app()->end();
				}
			}
		}
	}

	public function actionSubmitGrid()
	{

		if (isset($_POST['RateDomestic']))
		{
			foreach ($_POST['RateDomestic'] as $key => $data)
			{
				if (isset($data['id']))
					$model = RateDomestic::model()->findByPk($data['id']);
				else
					$model = new RateDomestic;

				$model->attributes = $data;

				$model->service_id = $_POST['service_id'];
				$model->origin_id = $_POST['origin_id'];

				if ($model->save())
				{
					$success = true;
					$message = 'Sukses menyimpan data';
				}
				else
				{
					$errorMessage = array();
					foreach ($model->getErrors() as $errorKey => $errors)
					{
						foreach ($errors as $error)
							$message['RatePrice_' . $errorKey . 'em'] = $error;
					}
					$success = false;
				}
			}
		}
		else
		{
			$success = true;
			$message = 'Tidak ada data';
		}
		echo CJSON::encode(array('success' => $success, 'message' => $message));
		Yii::app()->end();
	}
	/* render zone and area name on fancybox 
	 * 
	 */

	public function actionlihatZoneArea($postcode)
	{
		if (!Yii::app()->request->isAjaxRequest)
			$this->thrown;
		echo CJSON::encode(array('success' => true, 'message' => Area::model()->getZoneToolTips($postcode)));
		Yii::app()->end();
	}

	public function actionDelete($id = '')
	{
		if ($id != '')
		{
			if (Yii::app()->request->isPostRequest)
			{
				// we only allow deletion via POST request
				RateDomestic::model()->findByPk($id)->delete();

				// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
				if (!isset($_GET['ajax']))
					$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			}
			else
				throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		}
	}

	public function actionRatePriceAutoComplete()
	{
		if (Yii::app()->request->isAjaxRequest && isset($_GET['term']))
		{
			$list_all = array();

			$list_district = District::getListDistrict($_GET['term']);
			foreach ($list_district as $item)
			{
				$list_all[] = array(
					'value' => $item['value'],
					'label' => $item['label'],
					'mode' => 'district',
					'did' => $item['id'],
				);
			}

			$list_zone = Zone::getListZone($_GET['term']);
			foreach ($list_zone as $item)
			{
				$list_all[] = array(
					'value' => $item['value'],
					'label' => $item['label'],
					'mode' => 'zone',
					'did' => $item['did'],
					'zid' => $item['zid'],
				);
			}

			$this->setJsonHeader();
			echo CJSON::encode($list_all);
			Yii::app()->end();
		}
	}

	public function actionImportCSV()
	{
		ini_set('max_execution_time', 300);
		$this->layout = '//layouts/column2';
		$model = new UserImportForm;
		$failed_to_insert = array();
		$exist_postcode = array();
		$next_kg = -1;
		$first_kg = -1;
		$min_trans = -1;
		$max_trans = -1;
		$zone_id = -1;
		$district_id = -1;

		if (isset($_POST['UserImportForm']))
		{

			$model->attributes = $_POST['UserImportForm'];

			if ($model->validate())
			{

				$csvFile = CUploadedFile::getInstance($model, 'file');
				$tempLoc = $csvFile->getTempName();
				$rawdatas = file($tempLoc);
				try
				{
					$connection = Yii::app()->db;
					$transaction = $connection->beginTransaction();
					$sql = "INSERT INTO rate_domestic (service_id, origin_id, first_kg, next_kg, min_transit_time,max_transit_time,zone_id,district_id) VALUES(:service_id, :origin_id, :first_kg, :next_kg, :min_transit_time, :max_transit_time,:zone_id,:district_id)";
					$command = $connection->createCommand($sql);
					$command->bindValue(":service_id", $model->service_id, PDO::PARAM_INT);
					$command->bindValue(":origin_id", $model->origin_id, PDO::PARAM_INT);
					$command->bindParam(':zone_id', $zone_id);
					$command->bindParam(':district_id', $district_id);
					$command->bindParam(':first_kg', $first_kg);
					$command->bindParam(':next_kg', $next_kg);
					$command->bindParam(':min_transit_time', $min_trans);
					$command->bindParam(':max_transit_time', $max_trans);
					
					foreach ($rawdatas as $rates)
					{
						$rate = explode(',', $rates);
						if (!in_array($rate[0], $exist_postcode, true))
						{
							$exist_postcode[] = $rate[0];
//							var_dump($rate[1]);
//							if (!is_numeric($rate[1]) || !is_numeric($rate[2]) || !is_numeric($rate[3]))
//							{
//								$failed_to_insert[] = $rate[0];
//								continue;
//							}
							$postcode_data = Area::getZoneID($rate[0]);
							$checkZoneId = RateDomestic::checkZoneId($model->service_id, $model->origin_id, $postcode_data['zone_id']);
							if($checkZoneId)
								continue;
							$first_kg = $rate[1];
							$next_kg = $rate[2];
							$min_trans = $rate[3];
							$max_trans = $rate[4];
							$zone_id = $postcode_data['zone_id'];
							$district_id = $postcode_data['district_id'];
							if (!(!$postcode_data))
							{
								$exec = $command->execute();
							}
						}
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
