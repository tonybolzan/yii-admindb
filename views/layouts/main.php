<?php
$baseUrl = $this->module->assetsUrl;

$cs = Yii::app()->clientScript;
$cs->coreScriptPosition = CClientScript::POS_HEAD;
$cs->scriptMap = array();
$cs->registerCoreScript('jquery');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="language" content="en" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $baseUrl; ?>/css/main.min.css" />

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <?php flush(); ?>

    <body>
        <header class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="brand" href="#"><img src="<?php echo $baseUrl; ?>/img/logo.png" alt="Yii Admin DB"></a>
                </div>
            </div>
        </header>

        <div class="container-fluid">
            <?php echo $content; ?>
        </div>

        <footer>
            <div class="container-fluid">
                <hr>
                <p class="muted credit">
                    <?php echo Yii::powered(); ?>
                    <a class="pull-right" href="https://github.com/tonybolzan/Yii-AdminDB"><i class="label">https://github.com/tonybolzan/Yii-AdminDB</i></a>
                </p>
            </div>
        </footer>
    </body>
</html>