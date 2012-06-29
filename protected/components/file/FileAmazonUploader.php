<?php

/**
 * This class is to  be used for encoding, decoding and uploading Video to Amazon S3
 *
 * @author Alina Vasilevich
 */

class FileAmazonUploader {
    
    public $_amazonFile = NULL;
    
    public static function uploadToAmazonS3($uploadAmazonFileName, $localAmazonFileName = NULL, $amazonBucket) {
        try {
            $success = Yii::app()->s3->upload(Yii::app()->getBasePath().Yii::app()->params['commonMedia']['videoUploadConvertedPath'].$uploadAmazonFileName , $localAmazonFileName, $amazonBucket);
            if ($success) {
                $_amazonFile = array (
                                   'AmazonPath' => FileAmazonUploader::getAmazonS3FilePath($uploadAmazonFileName),
                                   'isAtAmazon' => 1
                               ); 
            } 
            return $_amazonFile;
        } 
        catch (S3Exception $e) {
            echo $e->getMessage();
            return false;
        }    
    }
    
    public static function getAmazonS3FilePath($fileName) {
        
        $fileUrl = "https://".S3::$endpoint."/".Yii::app()->s3->bucket."/".Yii::app()->s3->uploadPath.$fileName;
        $authenticatedUrl = Yii::app()->s3->s3Url($fileName);
        return $fileUrl;
        
    }
    
}

?>