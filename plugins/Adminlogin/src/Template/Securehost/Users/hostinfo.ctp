<?php $this->assign('title', $title);?>
<?php $this->assign('heading', $heading);?>
<?php
$this->Breadcrumbs->addCrumb('Manage Users', ['controller' => 'Users', 'action' => 'index']);
$this->Breadcrumbs->addCrumb($heading);
$sn = 1;
if(isset($this->request->query['page']))
{
    $sn = ($this->request->query['page'] == 1)?1:$num + 1;
}
?>
<div class="box-header">
   
    
</div>
<div class="box-body">
    <table id="example1" class="table table-bordered table-striped" style="width:60%;">
        <?php  if(empty($hostInfo)){?>
        <tbody>
            <tr>
                <td align="center" class="error">No record(s) found!</td>
            </tr>
        </tbody>
        <?php }else{?>
        <tr><td><strong>Host Information</strong></td></tr>
            <tr>
                <td> Host Name </td>
                <td><?= h($hostInfo->host_name);?></td>
            </tr>
            <tr>
                <td> Alternate Email </td>
                <td><?= h($hostInfo->alternate_email);?></td>
            </tr>
            <tr>
                <td> Alternate Number </td>
                <td><?= h($hostInfo->alternate_phone);?></td>
            </tr>
            <tr><td><strong>Emergency Contact Information</strong></td></tr>
            <tr>
                <td> Name </td>
                <td><?= h($hostInfo->emergency_contact);?></td>
            </tr>
            <tr>
                <td> Relationship </td>
                <td><?= h($hostInfo->relationship);?></td>
            </tr>
            <tr>
                <td> Contact Number </td>
                <td><?= h($hostInfo->emergency_phone_no);?></td>
            </tr>
            <tr><td><strong>Company Details</strong></td></tr>
            <tr>
                <td> Company Name </td>
                <td><?= h($hostInfo->name);?></td>
            </tr>
            <tr>
                <td> Company Migrate URL </td>
                <td><?= h($hostInfo->url);?></td>
            </tr>
            <tr>
                <td> Certifications </td>
                <td><?= h($hostInfo->certification);?></td>
            </tr>
            <tr>
                <td> About Company </td>
                <td style="width:50%;"><?= h($hostInfo->about);?></td>
            </tr>
            <tr>
                <td> Guest Waiver Form </td>
                <td><?php echo $this->Html->link($hostInfo->waiver.'  <i class="fa fa-download" style ="margin-left : 20px;"></i>',['controller' => 'users','action' => 'download',$hostInfo->id],['escape' => false]);?></td>
            </tr>
            <tr>
                <td> Company Images </td>
                <?php if(!empty($hostInfo->images)){
                    foreach($hostInfo->images as $image){
                        if(file_exists(WWW_ROOT.IMG_PATH.COMPANY_IMAGE.'mobile/'.$image)){
                            echo '<td>'.$this->Html->image(IMG_PATH.COMPANY_IMAGE.'mobile/'.$image,
                                ['style' => 'width : 100px']).'</td>';
                        }
                        
                    }
                }?>
                
            </tr>
        
        
        <?php }?>
    </table>
</div>    
<?php if(!empty($cmsPages)){?>
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
                            $options_pages .= "<option value=".$this->Url->build(["controller" => "CmsPages","action" => "index","num" => $val,])." selected>$val</option>";
                        else
                            $options_pages .= "<option value=".$this->Url->build(["controller" => "CmsPages","action" => "index","num" => $val,]).">$val</option>";
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
                    url: '<?= $this->Url->build(['controller' => 'AdminUsers', 'action' => 'delete'], true);?>',
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