<?php

/**
 * This class is to  be used for encoding, decoding Video
 *
 * @author Alina Vasilevich
 */

class FileConverter {
    
    public $_convertedFile = NULL;
    
    public static function encodeOriginalVideo ($fileName, $originalFolder, $convertFolder, $fileWidth, $fileHeight){
                
        $convertedFileName = substr($fileName,0,strlen($fileName)-4);
        $convertedFileName = $convertedFileName.'.flv';
        $thumbnailName = $convertedFileName.'.jpg';

        //convert the video to flv
        $ffmpegCommand = Yii::app()->params['commonMedia']['transcoderPath'].' -forceidx -of lavf -oac mp3lame -lameopts abr:br=56 -srate 22050 -ovc lavc -lavcopts vcodec=flv:vbitrate=250:mbd=2:mv0:trell:v4mv:cbp:last_pred=3 -vf scale='.$fileWidth.':'.$fileHeight.' -o "'.$convertFolder.$convertedFileName.'" "'.$originalFolder.$fileName.'"';  //$ffmpegPath."ffmpeg.exe -y -i \"".$videoPath.$file."\" -s ".$size." -ar ".$bitRate." -r ".$frameRate. " \"".$videoPath.$outfilename."\"";

        $result = shell_exec($ffmpegCommand);
        
        // get the thumbnail of file
        $ffmpegThumbnailCommand = Yii::app()->params['commonMedia']['transcoderPath']."ffmpeg.exe -y -ss 00:00:05 -vframes 1 -i \"".$originalFolder.$fileName."\" -s ".$size." -f image2  \"".Yii::app()->params['commonMedia']['videoTumbnailPath'].$fileName."\"";
        
        $pathToFile = $convertFolder.$convertedFileName;
        $fileType = CFileHelper::getMimeType($pathToFile); //filetype($pathToFile);
        $fileSize = filesize($pathToFile);
        
        try {
            $thumbnail = shell_exec($ffmpegThumbnailCommand);
            if (file_exists($pathToFile)) {
                $_convertedFile = array (
                                            'ConvertedPath' => Yii::app()->params['commonMedia']['videoUploadConvertedPath'].$convertedFileName,
                                            'ConvertedFileSize' => $fileSize,
                                            'ConvertedMimeType' => $fileType,
                                            'isConverted' => 1
                                        ); 
            }
            return $_convertedFile;
                    
        }
        catch(CDbException $e) // an exception is raised if a query fails
        {
            echo $e->getMessage();
            return false;
        }
       
    }
            
}

?>