<?php
$controller = $this->request->params['controller'];
$action = $this->request->params['action'];
if(in_array($action,['add','edit']))
{
    $action = 'index';
}
if(in_array($action,['adminAdd','adminEdit']))
{
    $action = 'userIndex';
}
if(in_array($action,['emailSettingEdit']))
{
    $action = 'emailSettingIndex';
}
if(in_array($action, ['discountOrderAdd']))
{
    $action = 'orderDiscountIndex';
}
if(in_array($action,['emailTemplateEdit']))
{
    $action = 'emailTemplate';
}

?>
<script type="text/javascript">
    $(document).ready(function() {

        
        var targetItem = $("ul.sidebar-menu li[data-item='<?php echo strtolower($controller) . '-'.strtolower($action); ?>'] a");
        $(targetItem).addClass("active");
        $(targetItem).parent().addClass("treeview active");
        $(targetItem).parent().parent().parent().addClass("treeview active");
    });
</script>
<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
               
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="treeview"><?php echo $this->Html->link(
                                               '<i class="fa fa-dashboard"></i><span>Dashboard</span>',
                                               ['controller' => 'Dashboard', 'action' => 'index'],
                                               ['escape' => false]
                    );?></li>
            
            <?php 
           
            foreach ($admin_menu as $menu): 
                
                ?>
                   <li class="treeview">
                       
                       <?php
                            if(!empty($permission))
                             {
                                  if(!in_array($menu['id'], $permission))
                                  {
                                      continue;
                                  }
                             }
                           if(isset($menu['children']))
                           {
                               echo $this->Html->link($menu['title'],[],['escape' => false]); 
                               ?><ul class="treeview-menu"> <?php
                               foreach($menu['children'] as $href)
                               {
                                   if(!empty($permission))
                                   {
                                        if(!in_array($href['id'], $permission))
                                        {
                                            continue;
                                        }
                                   }
                                   ?>
                                   <li data-item="<?php echo strtolower($href['controller']) . '-' . strtolower($href['action'])  ?>">
                                           <?php
                                           echo $this->Html->link(
                                               $href['title'],
                                               ['controller' => $href['controller'], 'action' => $href['action']],
                                               ['escape' => false]
                                           );
                                           ?>
                                   </li>
                                   <?php
                               }  
                               ?></ul><?php
                           } else {
                               
                                echo $this->Html->link(
                                               $menu['title'],
                                               ['controller' => $menu['controller'], 'action' => $menu['action']],
                                               ['escape' => false]
                                           );
                           }
                       ?>
                   </li> 
               <?php                
            endforeach;
            ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

