<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= isset($site['title']) ? $site['title'] : 'Admin'; ?> | <?= $this->fetch('title') ?></title>
        <?= $this->Html->meta('icon') ?>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <?= $this->Html->css('Adminlogin.bootstrap.min'); ?>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <?= $this->Html->css('Adminlogin.AdminLTE.min'); ?>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?= $this->Html->css('Adminlogin.customize'); ?>
        <?= $this->Html->script('Adminlogin.jquery-2.2.3.min'); ?>
        <?= $this->Html->script('Adminlogin.custom'); ?>
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <?php
                echo $this->Html->link(
                    $this->Html->image('Adminlogin.footer-logo.png', ['alt' => 'Logo','width'=>'100%','height'=>'100%']),
                    ['controller' => 'AdminUsers', 'action' => 'login'],
                    ['escape' => false]
                );
                ?>
            </div>
            <div class="login-box-body">
                <?= $this->fetch('content'); ?>                
            </div>
        </div>       
        <?= $this->Html->script('Adminlogin.bootstrap.min'); ?>
         <?= $this->element('validations/validation'); ?>
    </body>
</html>
