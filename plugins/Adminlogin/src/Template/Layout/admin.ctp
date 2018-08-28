<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= isset($site['title']) ? $site['title'] : 'Migrate Outfitters'; ?> | <?= $this->fetch('title') ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?= $this->Html->meta('icon') ?>
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
        <!-- Add fancyBox CSS files -->
        <?= $this->Html->css('Adminlogin.jquery.fancybox.css?v=2.1.5',['media' => "screen"]); ?>
        <?= $this->Html->css('Adminlogin.custom'); ?>      
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!--customize CSS-->
        <?= $this->Html->css('Adminlogin.customize'); ?>        
        <!-- jQuery 2.2.3 -->
        <?= $this->Html->script('Adminlogin.jquery-2.2.3.min'); ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <!-- Site wrapper -->
        <div class="loader" style="display:none;"></div>
        <div class="wrapper">
            <?= $this->element('header');?>
            <?= $this->element('sidebarMenu');?>
            <div class="content-wrapper">
                <?= $this->element('breadcrumb');?>
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div id="message_success">
                            <?= $this->Flash->render() ?>
                            </div>   
                            <div class="box">
                                <?= $this->fetch('content'); ?>                                               
                            </div>
                        </div>
                    </div>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <?= $this->element('footer');?>
        </div>        
        <?= $this->Html->script('Adminlogin.jquery.fancybox.js?v=2.1.5'); ?>
        <?= $this->Html->script('Adminlogin.jquery-ui'); ?>
        <!-- Bootstrap 3.3.6 -->
        <?= $this->Html->script('Adminlogin.bootstrap.min'); ?>
        <!-- SlimScroll -->
        <?= $this->Html->script('Adminlogin.jquery.slimscroll.min'); ?>
        <!-- FastClick -->
        <?= $this->Html->script('Adminlogin.fastclick.min'); ?>
        <!-- AdminLTE App -->
        <?= $this->Html->script('Adminlogin.app.min'); ?>
        <!--Custome JS-->
        <?= $this->Html->script('Adminlogin.customize'); ?> 
        <?= $this->Html->script('Adminlogin.custom'); ?>
        <!-- Add fancyBox JS files -->
        
        <script>
        $(document).ready(function() {
            if($(".various").length>0){
                $(".various").fancybox({
                    maxWidth	: 768,
                    /*maxHeight	: 600,*/
                    fitToView	: false,
                    width		: '70%',
                    height		: '70%',
                    autoSize	: true,
                    closeClick	: true,
                    openEffect	: 'none',
                    closeEffect	: 'none',
                    scrolling       : 'auto',
                    preload         : true,
                });
            }
        });
        </script>
    <style>
    th > a:after {
        content: " \f0dc";
        font-family: FontAwesome;
    }
    th > a.asc:after {
        content: " \f0dd";
        font-family: FontAwesome;
    }
    th > a.desc:after {
        content: " \f0de";
        font-family: FontAwesome;
    }
</style>
    </body>
</html>
