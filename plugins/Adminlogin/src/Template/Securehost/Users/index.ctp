<?php $this->assign('title', $title);?>
<?php $this->assign('heading', $heading);?>
<?php
$this->Breadcrumbs->addCrumb($heading);
$sn = 1;
if(isset($this->request->query['page']))
{
    $sn = ($this->request->query['page'] == 1)?1:(($num *$this->request->query['page']-$num)) + 1;
  
}
?>
<div class="box-header">
    <div class="col-sm-6" style="width: 20%;">
        <div id="example1_length" class="dataTables_length" style="float:left !important;">
            <?php 
            echo $this->Html->link('+ Add',
                ['controller' => 'Users', 'action' => 'add'],
                ['title' => 'Add User','class'=>'btn btn-primary']
            );
            ?>                      
        </div>
    </div>
    <div class="col-sm-6" style="width: 80%;">
        <?php
        echo $this->Form->create(
            null, 
            [
                'url' => ['controller' => 'Users', 'action' => 'index'],
                'type' => 'get',
                'name'  => 'searchForm'
            ]
        );
        ?>
        <div id="example1_filter" class="dataTables_filter" style="">
            <div id="example1_length" class="dataTables_length" style="padding: 2px;float:left !important;width:30%;text-align: left;">
                
            </div>
            <label style="padding: 2px;float:left;" class="form-group">
                <?php
                echo $this->Form->text(
                    'sk',
                    [
                        'required' => false,
                        'label' => false,
                        'placeholder' =>  'Name,Email...',
                        'class' =>  'form-control',       
                        'id' => 'sk',
                        'value' => $search_key,
                        'onchange' => "return trim(this)"
                    ]
                );
                ?>
            </label>
            <span style="padding: 2px;float:left;width:20%;">
            <?php
            $profileOptions = array(GUEST => 'Guest',HOST => 'Host',BOTH => 'Both');
            echo $this->Form->control(
                'profile',
                [
                    'required' => false,
                    'type' => 'select',
                    'label' => false,
                    'placeholder' =>  'State',
                    'class' =>  'form-control select2',       
                    'id' => 'state_id',
                    'style' => 'width: 100%;',
                    'options' => $profileOptions,
                    'empty' => '-- Select Profile --',
                    'escape'=>false,
                    'value'=> $profile
                ]
            );
        ?>
            </span>
            <span style="padding: 2px;float:left;">
                <?php
                echo $this->Form->button(
                    'Search',
                    [
                        'type' => 'submit',
                        'class' =>  'btn btn-primary  pull-right',
                    ]
                );
                ?>
            </span>
            <span style="padding: 2px;float:right;">
                <?php 
                    echo $this->Html->link('Reset',
                        ['controller' => 'Users', 'action' => 'index'],
                        ['title' => 'Reset Search','class'=>'btn btn-block btn-default','style' => "bottom:0px"]
                    );
            ?>
            </span>
        </div>
        <?php echo $this->Form->end();?>
    </div>
    
</div>
<div class="box-body">
    <table id="example1" class="table table-bordered table-striped">
        <?php  if(empty($users) || count($users)<=0){?>
        <tbody>
            <tr>
                <td colspan="6" align="center" class="error">No record(s) found!</td>
            </tr>
        </tbody>
        <?php }else{?>
        <thead>
            <tr>
                <th width="5%">S.No.</th>
                <th><?= $this->Paginator->sort('nickname', 'Name');?></th>
                <th><?= $this->Paginator->sort('email', 'Email');?></th>
                <th><?= $this->Paginator->sort('phone', 'Contact Number');?></th>
                <th><?= $this->Paginator->sort('role_id', 'Active Profiles');?></th>
                <th><?= 'Host Information';?></th>
                <th width="15%"><?= $this->Paginator->sort('created_on', 'Created Date');?></th>
                <th width="15%"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i=0;foreach($users as $user):$i++?>
            <tr id="detele_<?= $user->id;?>">
                <td><?= $sn++;?></td>
                <td><?= h($user->nickname);?></td>
                <td><?= $user->email;?></td>
                <td><?= $user->phone;?></td>
                <?php 
                $role = 'Inactive';
                $roles = array();
                
                if(!empty($user->user_roles)){
                    $userRoles = json_decode(json_encode($user->user_roles),true);
                    $roles = array_column($userRoles, 'role_id');
                    
                    if(in_array(GUEST, $roles) && in_array(HOST, $roles)){
                        $role = 'Both';
                    } else {
                        $role = in_array(GUEST,$roles) ? 'Guest' : 'Host';
                    }
                }
                ?>
                <td><?= $this->Html->link($role, ['controller' => 'Users','action'=>'profileStatus',$user->id],
                        ['class'=>"various",'data-fancybox-type'=>"iframe",'escape' => false,'target' => '_blank'] );?></td>
                <?php if($user->role_id == HOST || $user->role_id == BOTH){?>
                <td>
                    <?= $this->Html->link('Host Information',
                            ['action'=>'hostinfo', $user->id],
                            ['data-fancybox-type'=>"iframe",'escape' => false]
                    );  ?>
                </td>
                <?php } else {?>
                <td>N/A</td>
                    
                <?php }?>
                <td><?= date("m-d-Y",strtotime($user->created_on));?></td>
                <td>
                    <?php
                   
                    echo $this->Html->link('<i class="fa fa-fw fa-edit"></i>',
                        ['controller' => 'Users', 'action' => 'edit', $user->id],
                        [
                            'status' => 1,
                            'title' => 'Edit',
                            'escape' => false
                        ]
                    );
                    if($user->role_id != ADMIN)
                    {
                        echo '&nbsp;&nbsp;&nbsp;';
                        echo $this->Html->link('<i class="fa fa-trash-o"></i>',
                            "javascript::void(0);",
                            [
                                'class' => 'confirm',
                                'status' => 1,
                                'title' => 'Remove',
                                'value' => $user->id,
                                'escape' => false
                            ]
                        );
                    }
                    echo '&nbsp;&nbsp;&nbsp;';
                    echo $this->Html->link($user->is_block ? 'Unblock':'Block',
                            "javascript::void(0);",
                            [
                                'class' => 'block-unblock',
                                'is_block' => ($user->is_block)?0:1,
                                'title' => ($user->is_block)?'Unblock':'Block',
                                'value' => $user->id,
                                'escape' => false
                            ]
                        );
                    ?>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
        <?php }?>
    </table>
</div>    
<?php if(!empty($users)){?>
<div class="box-footer">                    
    <div class="col-sm-5">
        <?php 
        $total_records = ($this->Paginator->params()['count']);
        $total_pages  =  $this->Paginator->counter(array('format'=>'%pages%')); 
        $start = $this->Paginator->counter(array('format' => '%start%')); 
        $current_page =  $this->Paginator->counter(array('format'=>'%page%')); 
        $options_pages ='';
        if($total_records > $num){?>
         <div id="example1_length" class="dataTables_length">
            <label>
                Show entries
                <select class="form-control input-sm" name="example1_length" id="paging1" aria-controls="example1" onchange="window.location.href=this.value;">
                    <?php
                    foreach($pageArr as $val):
                        if($val==$num)
                            $options_pages .= "<option value=".$this->Url->build(["controller" => "users","action" => "index","num" => $val,])." selected>$val</option>";
                        else
                            $options_pages .= "<option value=".$this->Url->build(["controller" => "users","action" => "index","num" => $val,]).">$val</option>";
                    endforeach;
                    echo $options_pages;
                    ?>
                </select>
            </label>
        </div>
        <?php } ?>
    </div>
    <div class="col-sm-7">
        <div id="example1_paginate" class="dataTables_paginate paging_simple_numbers" style="float:right;">
        <?php 
        $total = $this->Paginator->counter('{{pages}}');
        if($total > 1) { ?>
            <ul class="pagination">
                <li id="example1_previous" class="paginate_button previous disabled"><?php echo $this->Paginator->prev(' Previous ',['class' => 'disabled']);?></li>
                <?php echo $this->Paginator->numbers();?>
                <li id="example1_next" class="paginate_button next disabled"><?php echo $this->Paginator->next(' Next ',['class' => 'disabled']);?></li> 
            </ul>    
        <?php } ?>
        </div>
    </div>
</div>
<?php }?>
<?= $this->Html->css('Adminlogin.sweetalert'); ?>
<?= $this->Html->script('Adminlogin.sweetalert.min'); ?>
<script>
$(document).ready(function(){
    
    //block unblock user
    $("a.block-unblock").on('click',function(){
        var id = $(this).attr('value');
        var is_block = $(this).attr('is_block');
        var title = (parseInt(is_block)) ? 'block':'Unblock';
        var object = $(this);
        swal({
            title: "Are you sure?",
            text: "You want to "+title+" the user.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, "+title+" it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) 
            {
                $.ajax({
                    type: "POST",
                    data: {id: id,is_block:is_block},
                    url: '<?= $this->Url->build(['controller' => 'Users', 'action' => 'blockUnblock'], true);?>',
                    dataType: 'json',
                    success: function(json) {
                        if(json.status==1){
                            $(object).text((parseInt(is_block) == 0) ? 'Block' :'Unblock' );
                            $(object).attr('is_block',(parseInt(is_block) == 0) ? 1 :0);
                            $(object).attr('title',(parseInt(is_block) == 0) ? 'Block' :'Unblock');
                            swal("User Status", json.msg, "success");
                            
                          
                        }else{
                            swal("Cancelled", json.msg, "error");
                        }
                    }
                });
            } else {
                swal("Cancelled", "Your record is safe :)", "error");
            }    
        });
        
    });
    $('a.confirm').on('click', function(e){
        var id = $(this).attr('value');
        //e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "You want to delete this record? This would delete it's corresponding data(s)  if any!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
                $.ajax({
                    type: "POST",
                    data: {id: id},
                    url: '<?= $this->Url->build(['controller' => 'Users', 'action' => 'delete'], true);?>',
                    dataType: 'json',
                    success: function(json) {
                        if(json.status==1){
                            swal("Deleted!", json.msg, "success");
                            $("#detele_"+id).remove();
                        }else{
                            swal("Cancelled", json.msg, "error");
                        }
                    }
                });
            } else {
                swal("Cancelled", "Your record is safe :)", "error");
            }    
        });
    }); 
    $('a.status').on('click', function(e){
        var id = $(this).attr('value');
        var status = $(this).attr('status');
        //e.preventDefault();
        
        swal({
            title: "Are you sure?",
            text: "You want to change status of this record? This would affect corresponding data(s) if any!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, change it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
                $.ajax({
                    type: "POST",
                    data: {id: id, status:status},
                    url: '<?= $this->Url->build(['controller' => 'AdminUsers', 'action' => 'status'], true);?>',
                    dataType: 'json',
                    success: function(json) {           
                        if(json.status==1){
                            swal("Changed!", json.msg, "success");
                            $("#status_"+id).empty();
                            $("#status_"+id).append('<i class="fa fa-fw fa-check"></i>');
                            //$("#status_"+id).attr('myvalue','1');
                            var newstr;
                            var str= $("#status_"+id).attr('status');
                            if(str=='0')
                                newstr=1;
                            else
                                newstr=0;
                                $("#status_"+id).attr('status',newstr); 
                            
                        }else if(json.status==2){
                            swal("Changed!", json.msg, "success");
                            $("#status_"+id).empty();
                            $("#status_"+id).append('<i class="fa fa-fw fa-close"></i>');
                            //$("#status_"+id).attr('myvalue','0');
                            
                            var newstr;
                            var str= $("#status_"+id).attr('status');
                            if(str=='0')
                                newstr=1;
                            else
                                newstr=0;
                            $("#status_"+id).attr('status',newstr);
                        }else{
                            swal("Cancelled", json.msg, "error");
                        }
                    } 
                });
            } else {
                swal("Cancelled", "Your record is safe :)", "error");
            }    
        });
    });    
});
</script>