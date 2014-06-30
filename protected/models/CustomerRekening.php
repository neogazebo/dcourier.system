<?php

/**
 * This is the model class for table "customer_rekening".
 *
 * The followings are the available columns in table 'customer_rekening':
 * @property integer $id
 * @property integer $customer_id
 * @property string $bank
 * @property string $cabang
 * @property string $rekening
 * @property string $nama
 *
 * The followings are the available model relations:
 * @property Customers $customer
 */
class CustomerRekening extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CustomerRekening the static model class
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
		return 'customer_rekening';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id', 'required'),
			array('customer_id', 'numerical', 'integerOnly'=>true),
			array('bank, cabang, rekening, nama', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, customer_id, bank, cabang, rekening, nama', 'safe', 'on'=>'search'),
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
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('web','ID'),
			'customer_id' => Yii::t('web','Customer'),
			'bank' => Yii::t('web','Bank'),
			'cabang' => Yii::t('web','Branch'),
			'rekening' => Yii::t('web','Rekening Number'),
			'nama' => Yii::t('web','Name'),
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('bank',$this->bank,true);
		$criteria->compare('cabang',$this->cabang,true);
		$criteria->compare('rekening',$this->rekening,true);
		$criteria->compare('nama',$this->nama,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}