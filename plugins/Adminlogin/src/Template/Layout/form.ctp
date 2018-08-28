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
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <?= $this->Html->css('Adminlogin._all-skins.min'); ?>
        <!-- Select2 -->
        <?= $this->Html->css('Adminlogin.select2.min'); ?>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!--customize CSS-->
        <?= $this->Html->css('Adminlogin.customize'); ?>   
        <?= $this->Html->css('Adminlogin.custom'); ?>   
        <!-- jQuery 2.2.3 -->
        <?= $this->Html->script('Adminlogin.jquery-2.2.3.min',['type'=>"text/javascript"]); ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <div class="loader" style="display:none;"></div>
        <!-- Site wrapper -->
        <div class="wrapper">
            <?= $this->element('header');?>
            <?= $this->element('sidebarMenu');?>
            <div class="content-wrapper">
                <?= $this->element('breadcrumb');?>
                <!-- Main content -->
                <section class="content">
                    <!-- Default box -->
                    <div class="box">
                        <div class="box-body">
                            <?= $this->fetch('content'); ?>
                        </div>
                    </div>
                    <!-- /.box -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <?= $this->element('footer');?>
        </div>        
        <!-- Bootstrap 3.3.6 -->
        <?= $this->Html->script('Adminlogin.bootstrap.min',['type'=>"text/javascript"]); ?>
        <!-- Select2 -->
        <?= $this->Html->script('Adminlogin.select2.full.min',['type'=>"text/javascript"]); ?>
        <!-- SlimScroll -->
        <?= $this->Html->script('Adminlogin.jquery.slimscroll.min',['type'=>"text/javascript"]); ?>
        <!-- FastClick -->
        <?= $this->Html->script('Adminlogin.fastclick.min',['type'=>"text/javascript"]); ?>
        <!-- AdminLTE App -->
        <?= $this->Html->script('Adminlogin.app.min',['type'=>"text/javascript"]); ?>   
        <!--Custome JS-->
        <?= $this->Html->script('Adminlogin.customize',['type'=>"text/javascript"]); ?> 
        <?= $this->Html->script('Adminlogin.phone_mask_js',['type'=>"text/javascript"]); ?> 
        <?= $this->Html->script('Adminlogin.inputmask.phone.extensions',['type'=>"text/javascript"]); ?> 
        <?= $this->element('validations/validation'); ?>
        <script type="text/javascript">
            $(function () {
                //Initialize Select2 Elements
                $(".select2").select2();
            });
        </script>
    </body>
</html>