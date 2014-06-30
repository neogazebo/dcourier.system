<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property integer $created
 * @property integer $updated
 * @property integer $access
 * @property string $salt
 * @property integer $active
 *
 * The followings are the available model relations:
 * @property Sessions[] $sessions
 * @property Watchdog[] $watchdogs
 */
class User extends CActiveRecord
{
	const USER_SYSTEM = 0;
	public $activeList;
	public $confirmPassword;
	public $newPassword;
	public $oldPassword;
	public $image;

	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
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
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, email,firstname,lastname', 'required', 'on' => 'create'),
			array('created, updated, access,active, nip', 'numerical', 'integerOnly' => true),
			array('username', 'length', 'max' => 45),
//			array('oldPassword', 'CUpdatePasswordValidator', 'newPasswordAttribute' => 'newPassword', 'savedPasswordAttribute' => 'password'),
			array('confirmPassword', 'compare', 'compareAttribute' => 'newPassword', 'message' => '{compareAttribute} harus diulang dengan tepat', 'on' => 'update'),
			array('newPassword,image', 'safe'),
			array('password,newPassword', 'length', 'max' => 80, 'min' => 6),
			array('email', 'length', 'max' => 255),
			array('password,confirmPassword', 'required', 'on' => 'create'),
			array('username,email', 'unique', 'on' => 'create'),
			array('salt, telp_office', 'length', 'max' => 20),
			array('firstname', 'length', 'max' => 50),
			array('lastname', 'length', 'max' => 60),
			array('telp_home,telp_office', 'length', 'max' => 16),
			array('timezone', 'length', 'max' => 25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, email, created, updated, access, salt, active', 'safe', 'on' => 'search'),
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
			'sessions' => array(self::HAS_MANY, 'Sessions', 'user_id'),
			'watchdogs' => array(self::HAS_MANY, 'Watchdog', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('model', 'ID'),
			'username' => Yii::t('model', 'Username'),
			'password' => Yii::t('model', 'Password'),
			'oldPassword' => Yii::t('model', 'Old Password'),
			'newPassword' => Yii::t('model', 'New Password'),
			'confirmPassword' => Yii::t('model', 'New Password Confirmation'),
			'email' => Yii::t('model', 'Email'),
			'created' => Yii::t('model', 'Created'),
			'updated' => Yii::t('model', 'Modified'),
			'access' => Yii::t('model', 'Access'),
			'salt' => Yii::t('model', 'Salt'),
			'active' => Yii::t('model', 'Active'),
			'firstname' => Yii::t('model', 'First Name'),
			'lastname' => Yii::t('model', 'Last Name'),
			'nip' => Yii::t('model', 'Employee ID'),
			'telp_home' => Yii::t('model', 'Home Phone Number'),
			'timezone' => Yii::t('model', 'Timezone'),
			'telp_office' => Yii::t('model', 'Office Phone Number'),
			'image' => Yii::t('model', 'Picture Profile'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('username', $this->username, true);
		$criteria->compare('password', $this->password, true);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('created', $this->created);
		$criteria->compare('updated', $this->updated);
		$criteria->compare('access', $this->access);
		$criteria->compare('salt', $this->salt, true);
		$criteria->compare('active', $this->active);
		$criteria->compare('firstname', $this->firstname, true);
		$criteria->compare('lastname', $this->lastname, true);
		$criteria->compare('nip', $this->nip, true);
		$criteria->compare('telp_home', $this->telp_home, true);
		$criteria->compare('telp_office', $this->telp_office, true);
		$criteria->compare('timezone', $this->timezone, true);
		$criteria->condition = 'id != 0';
		return new CActiveDataProvider($this, array(
					'criteria' => $criteria,
				));
	}

	protected function beforeSave()
	{
		if ($this->isNewRecord)
		{
			$this->created = time();
			$this->salt = $this->generateSalt();
			$this->password = sha1($this->password . md5($this->salt));
		}
		else
		{
			$this->updated = time();
			if ($this->newPassword != '')
			{
				$this->salt = $this->generateSalt();
				$this->password = sha1($this->newPassword . md5($this->salt));
			}
		}
		return parent::beforeSave();
	}

	public function validatePassword($password)
	{
		if ($this->password === sha1($password . md5($this->salt)))
		{
			return true;
		}
		return FALSE;
	}

	public function generateSalt($max = 18)
	{
		$characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$i = 0;
		$salt = "";
		do
		{
			$salt .= $characterList{mt_rand(0, strlen($characterList) - 1)};
			$i++;
		}
		while ($i <= $max);
		return $salt;
	}

	public function activeList()
	{
		$this->activeList = array(
			'1' => 'active',
			'0' => 'non active',
		);
		return $this->activeList;
	}

	public function getActive()
	{
		$this->activeList();
		return $this->activeList[$this->active];
	}

	public function listTimeZone()
	{
		return array(
			'Pacific/Midway' => "(GMT-11:00) Midway Island",
			'US/Samoa' => "(GMT-11:00) Samoa",
			'US/Hawaii' => "(GMT-10:00) Hawaii",
			'US/Alaska' => "(GMT-09:00) Alaska",
			'US/Pacific' => "(GMT-08:00) Pacific Time (US CA)",
			'America/Tijuana' => "(GMT-08:00) Tijuana",
			'US/Arizona' => "(GMT-07:00) Arizona",
			'US/Mountain' => "(GMT-07:00) Mountain Time (US CA)",
			'America/Chihuahua' => "(GMT-07:00) Chihuahua",
			'America/Mazatlan' => "(GMT-07:00) Mazatlan",
			'America/Mexico_City' => "(GMT-06:00) Mexico City",
			'America/Monterrey' => "(GMT-06:00) Monterrey",
			'Canada/Saskatchewan' => "(GMT-06:00) Saskatchewan",
			'US/Central' => "(GMT-06:00) Central Time (US CA)",
			'US/Eastern' => "(GMT-05:00) Eastern Time (US CA)",
			'US/East-Indiana' => "(GMT-05:00) Indiana (East)",
			'America/Bogota' => "(GMT-05:00) Bogota",
			'America/Lima' => "(GMT-05:00) Lima",
			'America/Caracas' => "(GMT-04:30) Caracas",
			'Canada/Atlantic' => "(GMT-04:00) Atlantic Time (Canada)",
			'America/La_Paz' => "(GMT-04:00) La Paz",
			'America/Santiago' => "(GMT-04:00) Santiago",
			'Canada/Newfoundland' => "(GMT-03:30) Newfoundland",
			'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
			'Greenland' => "(GMT-03:00) Greenland",
			'Atlantic/Stanley' => "(GMT-02:00) Stanley",
			'Atlantic/Azores' => "(GMT-01:00) Azores",
			'Atlantic/Cape_Verde' => "(GMT-01:00) Cape Verde Is.",
			'Africa/Casablanca' => "(GMT) Casablanca",
			'Europe/Dublin' => "(GMT) Dublin",
			'Europe/Lisbon' => "(GMT) Lisbon",
			'Europe/London' => "(GMT) London",
			'Africa/Monrovia' => "(GMT) Monrovia",
			'Europe/Amsterdam' => "(GMT+01:00) Amsterdam",
			'Europe/Belgrade' => "(GMT+01:00) Belgrade",
			'Europe/Berlin' => "(GMT+01:00) Berlin",
			'Europe/Bratislava' => "(GMT+01:00) Bratislava",
			'Europe/Brussels' => "(GMT+01:00) Brussels",
			'Europe/Budapest' => "(GMT+01:00) Budapest",
			'Europe/Copenhagen' => "(GMT+01:00) Copenhagen",
			'Europe/Ljubljana' => "(GMT+01:00) Ljubljana",
			'Europe/Madrid' => "(GMT+01:00) Madrid",
			'Europe/Paris' => "(GMT+01:00) Paris",
			'Europe/Prague' => "(GMT+01:00) Prague",
			'Europe/Rome' => "(GMT+01:00) Rome",
			'Europe/Sarajevo' => "(GMT+01:00) Sarajevo",
			'Europe/Skopje' => "(GMT+01:00) Skopje",
			'Europe/Stockholm' => "(GMT+01:00) Stockholm",
			'Europe/Vienna' => "(GMT+01:00) Vienna",
			'Europe/Warsaw' => "(GMT+01:00) Warsaw",
			'Europe/Zagreb' => "(GMT+01:00) Zagreb",
			'Europe/Athens' => "(GMT+02:00) Athens",
			'Europe/Bucharest' => "(GMT+02:00) Bucharest",
			'Africa/Cairo' => "(GMT+02:00) Cairo",
			'Africa/Harare' => "(GMT+02:00) Harare",
			'Europe/Helsinki' => "(GMT+02:00) Helsinki",
			'Europe/Istanbul' => "(GMT+02:00) Istanbul",
			'Asia/Jerusalem' => "(GMT+02:00) Jerusalem",
			'Europe/Kiev' => "(GMT+02:00) Kyiv",
			'Europe/Minsk' => "(GMT+02:00) Minsk",
			'Europe/Riga' => "(GMT+02:00) Riga",
			'Europe/Sofia' => "(GMT+02:00) Sofia",
			'Europe/Tallinn' => "(GMT+02:00) Tallinn",
			'Europe/Vilnius' => "(GMT+02:00) Vilnius",
			'Asia/Baghdad' => "(GMT+03:00) Baghdad",
			'Asia/Kuwait' => "(GMT+03:00) Kuwait",
			'Europe/Moscow' => "(GMT+03:00) Moscow",
			'Africa/Nairobi' => "(GMT+03:00) Nairobi",
			'Asia/Riyadh' => "(GMT+03:00) Riyadh",
			'Europe/Volgograd' => "(GMT+03:00) Volgograd",
			'Asia/Tehran' => "(GMT+03:30) Tehran",
			'Asia/Baku' => "(GMT+04:00) Baku",
			'Asia/Muscat' => "(GMT+04:00) Muscat",
			'Asia/Tbilisi' => "(GMT+04:00) Tbilisi",
			'Asia/Yerevan' => "(GMT+04:00) Yerevan",
			'Asia/Kabul' => "(GMT+04:30) Kabul",
			'Asia/Yekaterinburg' => "(GMT+05:00) Ekaterinburg",
			'Asia/Karachi' => "(GMT+05:00) Karachi",
			'Asia/Tashkent' => "(GMT+05:00) Tashkent",
			'Asia/Kolkata' => "(GMT+05:30) Kolkata",
			'Asia/Kathmandu' => "(GMT+05:45) Kathmandu",
			'Asia/Almaty' => "(GMT+06:00) Almaty",
			'Asia/Dhaka' => "(GMT+06:00) Dhaka",
			'Asia/Novosibirsk' => "(GMT+06:00) Novosibirsk",
			'Asia/Bangkok' => "(GMT+07:00) Bangkok",
			'Asia/Jakarta' => "(GMT+07:00) Jakarta",
			'Asia/Krasnoyarsk' => "(GMT+07:00) Krasnoyarsk",
			'Asia/Chongqing' => "(GMT+08:00) Chongqing",
			'Asia/Hong_Kong' => "(GMT+08:00) Hong Kong",
			'Asia/Irkutsk' => "(GMT+08:00) Irkutsk",
			'Asia/Kuala_Lumpur' => "(GMT+08:00) Kuala Lumpur",
			'Australia/Perth' => "(GMT+08:00) Perth",
			'Asia/Singapore' => "(GMT+08:00) Singapore",
			'Asia/Taipei' => "(GMT+08:00) Taipei",
			'Asia/Ulaanbaatar' => "(GMT+08:00) Ulaan Bataar",
			'Asia/Urumqi' => "(GMT+08:00) Urumqi",
			'Asia/Seoul' => "(GMT+09:00) Seoul",
			'Asia/Tokyo' => "(GMT+09:00) Tokyo",
			'Asia/Yakutsk' => "(GMT+09:00) Yakutsk",
			'Australia/Adelaide' => "(GMT+09:30) Adelaide",
			'Australia/Darwin' => "(GMT+09:30) Darwin",
			'Australia/Brisbane' => "(GMT+10:00) Brisbane",
			'Australia/Canberra' => "(GMT+10:00) Canberra",
			'Pacific/Guam' => "(GMT+10:00) Guam",
			'Australia/Hobart' => "(GMT+10:00) Hobart",
			'Australia/Melbourne' => "(GMT+10:00) Melbourne",
			'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
			'Australia/Sydney' => "(GMT+10:00) Sydney",
			'Asia/Vladivostok' => "(GMT+10:00) Vladivostok",
			'Asia/Magadan' => "(GMT+11:00) Magadan",
			'Pacific/Auckland' => "(GMT+12:00) Auckland",
			'Pacific/Fiji' => "(GMT+12:00) Fiji",
			'Asia/Kamchatka' => "(GMT+12:00) Kamchatka",
		);
	}

	public static function getUsername($id)
	{
		$model = User::model()->findByPk($id);
		return $model->username;
	}
	public static function getAvatar($id)
	{
		$model = User::model()->findByPk($id);
		if($model->poto==null || $model->poto=='')
			return '/img/no_avatar.png';
		else
			return 'media/user/'.$model->poto;
	}
	public static function getField($field,$id)
	{
		$model = User::model()->findByPk($id);
		return $model->$field;
	}

	public function upload($file)
	{
		if (!$file)
			return false;

		$uploaddir = 'media/user/';
		$target_name = md5($file['name']['image'] . time());
		$uploadfile = $file['tmp_name']['image'];


		Yii::import('application.extensions.image.Image');

		$image = new Image($uploadfile);

		$ext = $image->ext;
//		$image->resize(400, 100)->quality(75)->sharpen(20);
		$image_filename = $uploaddir . $target_name . '.' . $ext;
//		$image->save($image_filename);

		$image_filename = $uploaddir . $target_name . '.' . $ext;
		if (!move_uploaded_file($uploadfile, $image_filename))
		{
			return false;
		}
		$this->poto = $target_name . '.' . $ext;

		return true;
	}
}