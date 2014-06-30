<?php

/**
 * This is the model class for table "customer_comments".
 *
 * The followings are the available columns in table 'customer_comments':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $user_id
 * @property integer $created
 * @property string $comment
 *
 * The followings are the available model relations:
 * @property Customers $customers
 */
class CustomerComment extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CustomerComment the static model class
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
		return 'customer_comments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id,comment', 'required'),
			array('customer_id, user_id, created', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, customer_id, user_id, created, comment', 'safe', 'on'=>'search'),
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
			'customers' => array(self::BELONGS_TO, 'Customers', 'customer_id'),
			'user' => array(self::BELONGS_TO,'User','user_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_id' => 'Customers',
			'user_id' => 'User',
			'created' => 'Created',
			'comment' => 'Comment',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('created',$this->created);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function beforeSave()
	{
		if($this->isNewRecord)
		{
			$this->created = time();
		}
		return parent::beforeSave();
	}
}