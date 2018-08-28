<?= $this->assign('title', $title);?>
<?= $this->assign('heading', $heading);?>
<?php
$this->Html->addCrumb($heading);
?>
<div class="box-header">
    <div class="col-sm-6" style="width: 20%;">
        <div id="example1_length" class="dataTables_length" style="float:left !important;">
            <?php 
            echo $this->Html->link('+ Add',
                ['controller' => 'Roles', 'action' => 'admin_add'],
                ['title' => 'Add Role','class'=>'btn btn-primary']
            );
            ?>                      
        </div>
    </div>
</div>
<div class="box-body">
    <table id="example1" class="table table-bordered table-striped">
        <?php if(empty($roles)){?>
        <tbody>
            <tr>
                <td colspan="6" align="center" class="error">No record(s) found!</td>
            </tr>
        </tbody>
        <?php }else{?>
        <thead>
            <tr>
                <th width="5%">S.No.</th>
                <th><?= $this->Paginator->sort('name', 'Role Name');?></th>
                <th><?= $this->Paginator->sort('description', 'Description');?></th>
                <th>Permission</th>
                <th width="20%"><?= $this->Paginator->sort('created_on', 'Created');?></th>
                <th width="10%"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i=0; foreach($roles as $role):$i++?>
            <tr id="detele_<?= $role->id;?>">
                <td><?= $i;?></td>
                <td><?= h($role->name);?></td>
                <td><?= h($role->description);?></td>
                <td><?php 
                    if($role->id != ADMIN)
                    {
                        echo  $this->Html->link('<i class="fa fa-fw fa-credit-card"></i>',
                            ['action'=>'permission', $role->id],
                            ['class'=>"various",'data-fancybox-type'=>"iframe",'escape' => false]
                        ); 
                    }
                     ?>
                </td>
                <td><?= date("m-d-Y",strtotime($role->created_on));?></td>
                <td>
                    <?php
                    if($role->status == 1) { 
                        echo $this->Html->link('<i class="fa fa-fw fa-check"></i>',
                            "javascript:void(0)",
                            //['controller' => 'Roles', 'action' => 'status', $ag->id,0],
                            [
                                'class'=>'status',
                                'value' => $role->id,
                                'status' => 0,
                                'title' => 'Active',
                                'id' => 'status_'.$role->id,
                                'escape' => false
                            ]
                        );
                    }else{
                        echo $this->Html->link('<i class="fa fa-fw fa-close"></i>',
                            "javascript:void(0)",
                            //['controller' => 'Roles', 'action' => 'status', $ag->id,1],
                            [
                                'class'=>'status',
                                'value' => $role->id,
                                'status' => 1,
                                'title' => 'In Active',
                                'id' => 'status_'.$role->id,
                                'escape' => false
                            ]
                        );
                    }
                    echo '&nbsp;&nbsp;&nbsp;';
                    echo $this->Html->link('<i class="fa fa-fw fa-edit"></i>',
                        ['controller' => 'Roles', 'action' => 'adminEdit', $role->id],
                        [
                            'status' => 1,
                            'title' => 'Edit',
                            'escape' => false
                        ]
                    );
                    echo '&nbsp;&nbsp;&nbsp;';
                    echo $this->Html->link('<i class="fa fa-trash-o"></i>',
                        "javascript::void(0);",
                        [
                            'class' => 'confirm',
                            'status' => 1,
                            'title' => 'Remove',
                            'value' => $role->id,
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
<?php if(!empty($roles)){?>
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
                            $options_pages .= "<option value=".$this->Url->build(["controller" => "Roles","action" => "index","num" => $val,])." selected>$val</option>";
                        else
                            $options_pages .= "<option value=".$this->Url->build(["controller" => "Roles","action" => "index","num" => $val,]).">$val</option>";
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
<?= $this->Html->css('sweetalert'); ?>
<?= $this->Html->script('sweetalert.min'); ?>
<script>
$(document).ready(function(){
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
                    url: '<?= $this->Url->build(['controller' => 'Roles', 'action' => 'delete'], true);?>',
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
                    url: '<?= $this->Url->build(['controller' => 'Roles', 'action' => 'status'], true);?>',
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