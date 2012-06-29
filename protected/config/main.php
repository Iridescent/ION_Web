<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

return array (
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'KeyTag System',
    
    // preloading 'log' component
    'preload'=>array('log', 'zend'),

    // autoloading model and component classes
    'import'=>array(
        'application.config.*',
        'application.models.*',
        'application.models.common.*',
        'application.models.media.*',
        'application.models.query.*',
        'application.models.domain.*',
        'application.models.domain.job.*',
        'application.models.domain.person.*',
        'application.models.domain.program.*',
        'application.models.domain.survey.*',
        'application.models.domain.synchronization.*',
        'application.models.processors.*',
        'application.models.query.household.*',
        'application.models.query.person.*',
        'application.models.query.program.*',
        'application.models.query.survey.*',
        'application.models.query.user.*',
        'application.components.*',
        'application.components.document.*',
        'application.components.document.entity.*',
        'application.components.document.loaders.*',
        'application.components.document.validators.*',
        'application.components.remoting.*',
        'application.components.survey.*',
        'application.components.synchronization.*',
        'application.controllers.*',
        'application.vendors.*',
        'application.vendors.PHPExcel.Classes.*',
        'application.extensions.es3.s3',
        'application.extensions.es3.eS3',
        'application.components.file.*',
        
    ),

    'modules'=>array(
        // uncomment the following to enable the Gii tool	
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'tinka2901',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            //'ipFilters'=>array('127.0.0.3','::1'),
        ),
    ),
    
    'defaultController'=>'Home',

    // application components
    'components'=>array(
        'user'=>array(
            // enable cookie-based authentication
            'allowAutoLogin'=>true,
            'loginUrl' => array('/home/login'),
            'authTimeout' => 3600*2
        ),
        'authManager'=>array(
            'class'=>'CPhpAuthManager',
            //'defaultRoles'=>array('authenticated', 'guest'),
        ),
        's3' => array(
            'class'=>'application.extensions.es3.es3',
            'bucket' => 'tinkatest',
            'uploadPath' => 'video/',
            'aKey'=>'AKIAJAC3IZ34EKT26WFA', 
            'sKey'=>'23CwBNZW2lJcpttmRW1+ICZan0Cq78oAY6EDCjH7',
         ),
        
        // uncomment the following to enable URLs in path-format
        /*
        'urlManager'=>array(
            'urlFormat'=>'path',
            'rules'=>array(
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ),
        ),
        */
            
        'db'=>array(
            'connectionString' => 'mysql:host=192.168.242.209;dbname=keytag_darell',
            'emulatePrepare' => true,
            'enableProfiling' => true,
            'enableParamLogging' => true,
            'username' => 'keytaguser',
            'password' => '123',
            'charset' => 'utf8',
            /*'enableProfiling'=>true,
            'enableParamLogging' => true,*/
        ),
		
        'errorHandler'=>array(
            'errorAction'=>'home/error',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                    'categories'=>'system.*, application.*',
                ),
                /*array(
                     //   'class'=>'ext.db_profiler.DbProfileLogRoute',

                     //   'countLimit' => 5, // How many times the same query should be executed to be considered inefficient
                     //   'slowQueryMin' => 0.01, // Minimum time for the query to be slow
                    ),*/
                array (
                    'class' => 'CProfileLogRoute',
                    'categories' => 'ajax, system.db.CDbCommand',
                    //'showInFireBug' => true, //Показывать в 
                    'report' => 'summary',
                    'enabled' => true,
                   // 'ignoreAjaxInFireBug' => true,
                    'levels'=>'trace, profile, info',
                )

                /*array(
                    'class'=>'CProfileLogRoute',
                    'levels'=>'profile',
                    'enabled'=>true,
                ),*/
                // uncomment the following to show log messages on web pages
                /*array(
                        'class'=>'CWebLogRoute',
                ),*/
            ),
        ),
        
        'zend'=>array(
            'class'=>'application.extensions.zend.EZendAutoloader',
        ),
            
        'clientScript' => array(
            'scriptMap' => array(
                'jquery.js' => false,
            )),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
   
    'params'=>array(
            'commonMedia' => array(
		// this is used in contact page
		'adminEmail'=>'admin@keytag.com',
                // Video and Image uploading and converting parameters
                'maxFileSize'=> 50000000,
                'convertWidth' => 480,
                'convertHeight' => 270,
                'imageUploadPath'=>'/data/files/image/',
                'imageThumbnailPath'=>'/data/files/image/thumbnail/',
                'videoUploadOriginalPath'=>'/data/files/video/original/',
                'videoUploadConvertedPath'=>'/data/files/video/converted/',
                'videoThumbnailPath'=>'/data/files/video/thumbnail',
                'transcoderPath'=> 'C:/mencoder/mencoder.exe',
                'encodeCommand'=> '!cmd_path -forceidx -of lavf -oac mp3lame -lameopts abr:br=56 -srate 22050 -ovc lavc -lavcopts vcodec=flv:vbitrate=250:mbd=2:mv0:trell:v4mv:cbp:last_pred=3 -vf scale=!width:!height -o !convertfile !videofile',
                'videoThumnbailCommand'=>'-i !videofile -an -y -f mjpeg -ss !seek -vframes 1 !thumbfile'
            ),
            
        ),
        
);