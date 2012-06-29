<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/attendance.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/js-styling/jquery-1_7.js"></script>
        
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
    <body>
        <div style="width: 100%; margin: 0 auto; padding: 0px;">            
           
            <div class="main">
                <?php if (!Yii::app()->user->isGuest): ?>                            
                    <?php echo $content; ?>
                <?php endif ?>
                <script type="text/javascript">
                    $('#content-reporting').css({
                        'width' : $(document).width() - 60
                    });
                </script>

            </div>
            <div class="footer"></div>
        </div>
    </body>
</html>
