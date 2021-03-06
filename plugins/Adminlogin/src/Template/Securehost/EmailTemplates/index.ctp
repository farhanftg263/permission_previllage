<?php $this->assign('title', $title);?>
<?php $this->assign('heading', $heading);?>
<?php
$this->Breadcrumbs->addCrumb($heading);
?>
<div class="box-header">
    <div class="col-sm-6" style="width: 20%;">
        <div id="example1_length" class="dataTables_length" style="float:left !important;">
            <?php 
            echo $this->Html->link('+ Add',
                ['controller' => 'EmailTemplates', 'action' => 'add'],
                ['title' => 'Add Email Template','class'=>'btn btn-primary']
            );
            ?>                      
        </div>
    </div>
    <div class="col-sm-6" style="width: 80%;">
        <?php
        echo $this->Form->create(
            null, 
            [
                'url' => ['controller' => 'EmailTemplates', 'action' => 'index'],
                'type' => 'get',
                'name'  => 'searchForm'
            ]
        );
        ?>
        <div id="example1_filter" class="dataTables_filter" style="">
            <div id="example1_length" class="dataTables_length" style="padding: 2px;float:left !important;width:40%;text-align: left;">
                
            </div>
            <label style="padding: 2px;float:left;" class="form-group">
                <?php
                echo $this->Form->text(
                    'sk',
                    [
                        'required' => false,
                        'label' => false,
                        'placeholder' =>  'Slug/Email Title...',
                        'class' =>  'form-control',       
                        'id' => 'sk',
                        'value' => $search_key,
                        'onchange' => "return trim(this)"
                    ]
                );
                ?>
            </label>
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
            <span style="padding: 2px;float:right;margin-right:45px;">
                <?php 
                    echo $this->Html->link('Reset',
                        ['controller' => 'EmailTemplates', 'action' => 'index'],
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
        <?php  if(empty($emailTemplates) || count($emailTemplates)<=0){?>
        <tbody>
            <tr>
                <td colspan="6" align="center" class="error">No record(s) found!</td>
            </tr>
        </tbody>
        <?php }else{?>
        <thead>
            <tr>
                <th width="5%">S.N.</th>
                <th width="15%"><?= $this->Paginator->sort('slug', 'Slug',array('escape' => false));?></th>
                <th width="25%"><?= $this->Paginator->sort('title', 'Email Title',array('escape' => false));?></th>
                <th width="30%"><?= $this->Paginator->sort('subject', 'Subject',array('escape' => false));?></th>                
                <th width="15%"><?= $this->Paginator->sort('created_on', 'Created',array('escape' => false));?></th>
                <th width="10%"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i=0;foreach($emailTemplates as $email):$i++?>
            <tr id="detele_<?= $email->id;?>">
                <td><?= $i;?></td>
                <td><?= $this->Html->link($email->slug, ['action'=>'view', $email->id],['class'=>"various",'data-fancybox-type'=>"iframe",'escape' => false] );?></td>
                <td><?= h($email->title);?></td>
                <td><?= h($email->subject);?></td>                
                <td><?= date("jS M, Y",strtotime($email->created_on));?></td>
                <td>
                    <?php
                    if($email->status == 1) { 
                        echo $this->Html->link('<i class="fa fa-fw fa-check"></i>',
                            "javascript:void(0)",
                            [
                                'class'=>'status',
                                'value' => $email->id,
                                'status' => 0,
                                'title' => 'Active',
                                'id' => 'status_'.$email->id,
                                'escape' => false
                            ]
                        );
                    }else{
                        echo $this->Html->link('<i class="fa fa-fw fa-close"></i>',
                            "javascript:void(0)",
                            [
                                'class'=>'status',
                                'value' => $email->id,
                                'status' => 1,
                                'title' => 'Inactive',
                                'id' => 'status_'.$email->id,
                                'escape' => false
                            ]
                        );
                    }
                    echo '&nbsp;&nbsp;&nbsp;';
                    echo $this->Html->link('<i class="fa fa-fw fa-edit"></i>',
                        ['controller' => 'EmailTemplates', 'action' => 'edit', $email->id],
                        [                            
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
                            'value' => $email->id,
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
<?php if(!empty($emailTemplates)){?>
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
                            $options_pages .= "<option value=".$this->Url->build(["controller" => "EmailTemplates","action" => "index","num" => $val,])." selected>$val</option>";
                        else
                            $options_pages .= "<option value=".$this->Url->build(["controller" => "EmailTemplates","action" => "index","num" => $val,]).">$val</option>";
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
                    url: '<?= $this->Url->build(['controller' => 'EmailTemplates', 'action' => 'delete'], true);?>',
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
                    url: '<?= $this->Url->build(['controller' => 'EmailTemplates', 'action' => 'status'], true);?>',
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
