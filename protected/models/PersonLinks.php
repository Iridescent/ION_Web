<?php

/**
 * This is the model class for table "personlinks".
 *
 * The followings are the available columns in table 'personlinks':
 * @property integer $ID
 * @property integer $PersonId
 * @property string $Url
 */
class PersonLinks extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Personsubtype the static model class
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
		return 'personlinks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('PersonId', 'numerical', 'integerOnly'=>true),
			//array('Name', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, PersonId, Url', 'safe', 'on'=>'search'),
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
                    'person' => array(self::BELONGS_TO, 'Person', 'PersonId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'PersonId' => 'Person Id',
			'Url' => 'Url',
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

		$criteria->compare('ID',$this->ID);
		$criteria->compare('PersonId',$this->PersonId);
		$criteria->compare('Url',$this->Url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public static function getLinks($id = null)
        {
            $connection=Yii::app()->db;

            $links_sql = "SELECT * FROM personlinks WHERE PersonId = ".$id;
            $links_command = $connection->createCommand($links_sql);
            $links_rows = $links_command->queryAll();         
            return $links_rows;
        }
        
        public static function deleteLinks($id = null)
        {
            try
            {
                $connection=Yii::app()->db;

                $links_sql = "DELETE FROM personlinks WHERE PersonId = '".$id."'";
                $links_command = $connection->createCommand($links_sql);
                $links_rows_bool = $links_command->execute();         
                return $links_rows_bool;
            }catch(Exception $e)
            {
                error_log($e->getMessage());
            }
            
        }
        public function insertLinks($personId=null, $links=array())
        {
           try
            {
                $connection=Yii::app()->db;
                
                if (count($links)){
                    foreach ($links as $key => $value) {
                        $sql = " INSERT INTO personlinks (PersonId, Url) VALUES (".$personId.",'".$value."');"; 
                        $command = $connection->createCommand($sql); 
                        $command->execute();               
                    };
                    return true;
                }else{
                    return false;
                }
                
                return true;
            }catch(Exception $e)
            {
                error_log($e->getMessage());
            }
        
        }
}