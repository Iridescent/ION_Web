<?php

class File extends BaseModel {
    
    public $instance;

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'files';
    }

    public function rules() {
        return array(
            array('instance', 'file', 'on'=>'insert', 'types'=>'avi,flv,mp4,mpg,mpeg,wma,wmv,mov,3gp,jpg,jpeg,png,gif', 'maxSize' => Yii::app()->params['commonMedia']['maxFileSize'],'allowEmpty' => false),
            array('isConverted, isAtAmazon, UpdateUserId', 'numerical', 'integerOnly'=>true),
            array('OriginalPath, ConvertedPath, AmazonPath', 'length', 'max'=>255),
            array('OriginalMimeType, ConvertedMimeType', 'length', 'max'=>100),
            array('OriginalFileSize, ConvertedFileSize', 'length', 'max'=>20),
            array('LastUpdated', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('ID, OriginalPath, ConvertedPath, AmazonPath, OriginalMimeType, OriginalFileSize, ConvertedMimeType, ConvertedFileSize, isConverted, isAtAmazon, LastUpdated, UpdateUserId', 'safe', 'on'=>'search'),
        );
    }
}