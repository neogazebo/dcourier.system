<?php

/**
 * This is the model class for table "contacts".
 *
 * The followings are the available columns in table 'contacts':
 * @property integer $id
 * @property integer $created
 * @property integer $updated
 * @property string $parent_model
 * @property integer $parent_id
 * @property string $full_name
 * @property string $birth_place
 * @property string $dob
 * @property string $phone1
 * @property string $phone2
 * @property string $fax
 * @property string $email
 * @property string $address
 * @property string $city
 * @property integer $post
 * @property string $facebook
 * @property string $twitter
 * @property string $jabatan
 */
class Contact extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return Contact the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contacts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('created, updated, parent_id, postal', 'numerical', 'integerOnly' => true),
				array('postal','length','min' => 5,'max'=>5),
				array('gmap_lat,gmap_long','numerical','integerOnly'=> false),
				array('parent_model, full_name, address', 'length', 'max' => 255),
				array('country, birth_place, phone1, phone2, fax', 'length', 'max' => 45),
				array('facebook, twitter','length','max'=>100),
				array('jabatan','length','max'=>60),
				array('province','length','max' => 80),
				array('email, city', 'length', 'max' => 100),
//				array('phone1', 'required'),
				array('email', 'email'),
				array('email', 'unique', 'on' => 'insert'),
				array('dob', 'safe'),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, created, updated, parent_model, parent_id, full_name, birth_place, dob, phone1, phone2, fax, email, address, city, postal', 'safe', 'on' => 'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				'id' => Yii::t('model', 'ID'),
				'created' => Yii::t('model', 'Created'),
				'updated' => Yii::t('model', 'Updated'),
				'parent_model' => Yii::t('model', 'Parent Model'),
				'parent_id' => Yii::t('model', 'Parent'),
				'full_name' => Yii::t('model', 'Contact Name'),
				'birth_place' => Yii::t('model', 'Birth Place'),
				'dob' => Yii::t('model', 'Date of birth'),
				'phone1' => Yii::t('model', 'Phone'),
				'phone2' => Yii::t('model', 'Phone#2'),
				'fax' => Yii::t('model', 'Fax'),
				'email' => Yii::t('model', 'Email'),
				'address' => Yii::t('model', 'Address'),
				'city' => Yii::t('model', 'City'),
				'postal' => Yii::t('model', 'Poscode'),
				'facebook' => 'Facebook',
				'twitter' => 'Twitter',
				'jabatan' => Yii::t('model','Occupation')
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
		$criteria->compare('created', $this->created);
		$criteria->compare('updated', $this->updated);
		$criteria->compare('parent_model', $this->parent_model, true);
		$criteria->compare('parent_id', $this->parent_id);
		$criteria->compare('full_name', $this->full_name, true);
		$criteria->compare('birth_place', $this->birth_place, true);
		$criteria->compare('dob', $this->dob, true);
		$criteria->compare('phone1', $this->phone1, true);
		$criteria->compare('phone2', $this->phone2, true);
		$criteria->compare('fax', $this->fax, true);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('address', $this->address, true);
		$criteria->compare('city', $this->city, true);
		$criteria->compare('postal', $this->postal);

		return new CActiveDataProvider($this, array(
								'criteria' => $criteria,
						));
	}

	protected function beforeSave()
	{
		if ($this->isNewRecord)
		{
			$this->created = time();
		}
		else
		{
			$this->updated = time();
		}
		if(!empty($this->dob))
			$this->dob = date('Y-m-d', strtotime($this->dob));
		else
			$this->dob = date('Y-m-d', time());

		return parent::beforeSave();
	}

}