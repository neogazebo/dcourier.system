<?php

/**
 * This is the model class for table "invoice".
 *
 * The followings are the available columns in table 'invoice':
 * @property integer $id
 * @property integer $tgl_terbit
 * @property string $tgl_pembayaran
 * @property string $tgl_jatuh_tempo
 * @property integer $customer_id
 */
class Invoices extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Invoices the static model class
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
		return 'invoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tgl_terbit, customer_id', 'numerical', 'integerOnly'=>true),
			array('tgl_pembayaran, tgl_jatuh_tempo', 'safe'),
			array('tgl_pembayaran','required','on'=>'update'),
			array('total','length','max'=>12),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tgl_terbit, tgl_pembayaran, tgl_jatuh_tempo, customer_id', 'safe', 'on'=>'search'),
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
			'transaction' => array(self::HAS_MANY,'Transaction','invoice_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('web','ID'),
			'tgl_terbit' => Yii::t('web','Created'),
			'tgl_pembayaran' => Yii::t('web','Payment Date'),
			'tgl_jatuh_tempo' => Yii::t('web','Due Date'),
			'customer_id' => Yii::t('web','Customer ID'),
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
		$criteria->compare('tgl_terbit',$this->tgl_terbit);
		$criteria->compare('tgl_pembayaran',$this->tgl_pembayaran,true);
		$criteria->compare('tgl_jatuh_tempo',$this->tgl_jatuh_tempo,true);
		$criteria->compare('customer_id',$this->customer_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function beforeSave()
	{
		if($this->isNewRecord)
		{
			$this->tgl_terbit = time();
		}
		else
		{
			if(!empty($this->tgl_pembayaran))
				$this->tgl_pembayaran = date('Y-m-d', strtotime($this->tgl_pembayaran));
		}
		return parent::beforeSave();
	}
	
	public function beforeDelete()
	{
		Transaction::model()->updateAll(array('invoice_id'=>NULL),'invoice_id = :invoice_id',array(':invoice_id' => $this->id));
		return parent::beforeDelete();
	}
}