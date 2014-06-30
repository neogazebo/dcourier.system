 <?php

/**
 * This is the model class for table "rate_company".
 *
 * The followings are the available columns in table 'rate_company':
 * @property integer $id
 * @property string $name
 * @property integer $created
 * @property integer $updated
 * @property string $code
 * @property integer $is_international
 * @property integer $is_city
 * @property integer $is_domestic
 *
 * The followings are the available model relations:
 * @property ServiceDetail[] $serviceDetails
 */
class RateCompany extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return RateCompany the static model class
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
        return 'rate_company';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
						array('id, created, updated, is_international, is_city, is_domestic', 'numerical', 'integerOnly'=>true),
						array('name','required'),
            array('name', 'length', 'max'=>255),
            array('code', 'length', 'max'=>3),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, created, updated, code, is_international, is_city, is_domestic', 'safe', 'on'=>'search'),
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
            'rateCompanyServices' => array(self::HAS_MANY, 'RateCompanyService', 'rate_company_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'created' => 'Created',
            'updated' => 'Updated',
            'code' => 'Code',
            'is_international' => 'Is International',
            'is_city' => 'Is City',
            'is_domestic' => 'Is Domestic',
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
        $criteria->compare('name',$this->name,true);
        $criteria->compare('created',$this->created);
        $criteria->compare('updated',$this->updated);
        $criteria->compare('code',$this->code,true);
        $criteria->compare('is_international',$this->is_international);
        $criteria->compare('is_city',$this->is_city);
        $criteria->compare('is_domestic',$this->is_domestic);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
} 