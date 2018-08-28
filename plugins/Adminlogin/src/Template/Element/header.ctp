<header class="main-header">
    <!-- Logo -->
    <?php
    echo $this->Html->link(
        '<span class="logo-mini"><b>M</b></span><span class="logo-lg"><b>Migrate Outfitters</b></span>',
        ['controller' => 'dashboard', 'action' => 'index'],
        ['class'=>'logo','escape' => false]
    );
    ?>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="javascript:void(0)" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown  user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?= $this->Html->image('Adminlogin.logo.png', ['alt' => 'User Image', 'class'=>'user-image']);?>
                        <span class="hidden-xs"><?= $auth['auth_username']?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <?= $this->Html->image('Adminlogin.logo.png', ['alt' => 'User Image', 'class'=>'img-circle','style'=>'text-align:center; margin:auto']);?>
                            <p>
                              <?= $auth['auth_user_fullname']?>
                              <small>Member since <?= date("F, Y",strtotime($auth['auth_user_created']));?></small>
                          </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?php
                                echo $this->Html->link(
                                    'Change Password',
                                    ['controller' => 'AdminUsers', 'action' => 'changepassword'],
                                    ['class'=>'btn btn-default btn-flat','escape' => false]
                                );
                                ?>
                            </div>
                            <div class="pull-right">
                                <?php
                                echo $this->Html->link(
                                    'Logout',
                                    ['controller' => 'AdminUsers', 'action' => 'logout'],
                                    ['class'=>'btn btn-default btn-flat','escape' => false]
                                );
                                ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
