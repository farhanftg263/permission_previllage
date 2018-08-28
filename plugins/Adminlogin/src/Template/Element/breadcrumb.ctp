<section class="content-header">
    <h1><?= $this->fetch('heading');?></h1>
    <?php
//    echo $this->Html->getCrumbList(
//        [
//            'escape' => false,
//            'firstClass' => false,
//            'lastClass' => 'active',
//            'class' => 'breadcrumb',
//            'url' => ['controller' => 'Dashboard', 'action' => 'index'],
//        ],
//        '<i class="fa fa-dashboard"></i> Home'
//    );
    ?>
    <?php echo
    $this->Breadcrumbs->getCrumbs(false, [
        'text' => '<i class="fa fa-dashboard"></i> '.__('Home'),
        'url' => ['controller' => 'Dashboard', 'action' => 'index'],
    ]);
    ?>
    
     <?php
//   echo $this->Html->getCrumbs('&raquo;', [
//        'text' => '<i class="fa fa-dashboard"></i> '.__('Home'),
//        'url' => ['controller' => 'Dashboard', 'action' => 'index'],
//        [
//            'escape' => false,
//            'firstClass' => false,
//            'lastClass' => 'active',
//            'class' => 'breadcrumb',
//        ]
//    ]);
    ?>
</section>