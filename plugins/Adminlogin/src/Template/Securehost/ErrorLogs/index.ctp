<?php $this->assign('title', $title);?>
<?php $this->assign('heading', $heading);?>
<?php
$this->Breadcrumbs->addCrumb($heading);
?>
<div class="box-header">
      
</div>
<div class="box-body">
    <table id="example1" class="table table-bordered table-striped"  style="width:100%">
        <?php  if(empty($errorLogs) || count($errorLogs)<=0){?>
        <tbody>
            <tr>
                <td colspan="8" align="center" class="error">No record(s) found!</td>
            </tr>
        </tbody>
        <?php }else{?>
        <thead>
            <tr>
                <th width="5%" style="padding: 5px;">S.N.</th>
                <th width="19%" style="padding: 5px;"><?= $this->Paginator->sort('message','Message <i class="fa fa-sort-down"></i>',array('escape' => false));?></th>
                <th width="20%" style="padding: 5px;"><?= $this->Paginator->sort('file','File <i class="fa fa-sort-down"></i>',array('escape' => false));?></th>
                <th width="10%" style="padding: 5px;"><?= $this->Paginator->sort('line','Line <i class="fa fa-sort-down"></i>',array('escape' => false));?></th>
                <th width="10%" style="padding: 5px;"><?= $this->Paginator->sort('referer','Referer <i class="fa fa-sort-down"></i>',array('escape' => false));?></th>
                <th width="15%" style="padding: 5px;"><?= $this->Paginator->sort('browser','Browser <i class="fa fa-sort-down"></i>',array('escape' => false));?></th>

                <th width="13%" style="padding: 5px;"><?= $this->Paginator->sort('created','Created <i class="fa fa-sort-down"></i>',array('escape' => false));?></th>

                <th width="8%" style="padding: 5px;"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i=0;foreach($errorLogs as $errorLog):$i++?>
            <tr id="detele_<?= $errorLog->id;?>">
                <td> <?= $i;?></td>
                <td style="padding: 4px; white-space: wrap; word-break: break-word;"><?= h($errorLog->message) ?></td>
                <td style="padding: 4px; white-space: wrap; word-break: break-word;"><?= h($errorLog->file) ?></td>
                <td style="padding: 4px; white-space: wrap; word-break: break-word;"><?= h($errorLog->line) ?></td>
                <td style="padding: 4px; white-space: wrap; word-break: break-word;"><?= h($errorLog->referer) ?></td>
                <td style="padding: 4px; white-space: wrap; word-break: break-word;"><?= h($errorLog->browser) ?></td>                        
                <td style="padding: 4px; white-space: wrap; word-break: break-word;"><?= date("jS M, Y",strtotime($errorLog->created));?></td>
                <td>
                    <?php   
                    echo $this->Html->link('<i class="fa fa-trash-o"></i>',
                        "javascript::void(0);",
                        [
                            'class' => 'confirm',
                            'status' => 1,
                            'title' => 'Remove',
                            'value' => $errorLog->id,
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
<?php if(!empty($errorLogs)){?>
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
                            $options_pages .= "<option value=".$this->Url->build(["controller" => "ErrorLogs","action" => "index","num" => $val,])." selected>$val</option>";
                        else
                            $options_pages .= "<option value=".$this->Url->build(["controller" => "ErrorLogs","action" => "index","num" => $val,]).">$val</option>";
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
                    url: '<?= $this->Url->build(['controller' => 'ErrorLogs', 'action' => 'delete'], true);?>',
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
     
});
</script>