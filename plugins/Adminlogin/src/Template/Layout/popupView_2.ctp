<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= isset($site['title']) ? $site['title'] : 'Migrate Outfitters'; ?> | <?= $this->fetch('title') ?></title>
        <?= $this->Html->meta('icon') ?>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <?= $this->Html->css('Adminlogin.bootstrap.min'); ?>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <?= $this->Html->css('Adminlogin.AdminLTE.min'); ?>        
        <!-- Add fancyBox CSS files -->
        <?= $this->Html->css('Adminlogin.jquery.fancybox.css?v=2.1.5',['media' => "screen"]); ?>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <?= $this->Html->css('Adminlogin.jstree'); ?>
        <![endif]-->        
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <!-- Site wrapper -->
        <div class="wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <?= $this->fetch('heading');?>
                </h1> 
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <?= $this->Flash->render() ?>
                        <div class="box">
                            <?= $this->fetch('content'); ?>                                               
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->          
        </div>
        <!-- jQuery 2.2.3 -->
        <?= $this->Html->script('Adminlogin.jquery-2.2.3.min'); ?>
        <?= $this->Html->script('Adminlogin.custom'); ?>
        <!-- Bootstrap 3.3.6 -->
        <?= $this->Html->script('Adminlogin.bootstrap.min'); ?>
        <?= $this->Html->script('Adminlogin.jstree.min'); ?>
        <?= $this->Html->script('Adminlogin.jquery.fancybox.js?v=2.1.5'); ?>        
    </body>
</html>
