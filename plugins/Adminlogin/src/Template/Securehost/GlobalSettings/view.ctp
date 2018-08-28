<?php $this->assign('title', $title);?>
<?php $this->assign('heading', $heading);?>
                        
<table id="example1" class="table table-bordered table-striped">
    <tbody>
        <tr>
            <td width="15%">Reference</td>
            <td width="2%">:</td>
            <td width="83%"><?= $global_setting->reference;?></td>
        </tr>
        <tr>
            <td>Data Type</td>
            <td>:</td>
            <td><?= $global_setting->datatype;?></td>
        </tr>
        <tr>
            <td>Value</td>
            <td>:</td>
            <td><?= $global_setting->value;?></td>
        </tr>          
              
        <tr>
            <td>Status</td>
            <td>:</td>
            <td><?= ($global_setting->status==1)?'Active':'Inactive';?></td>
        </tr>  
        <tr>
            <td>Created</td>
            <td>:</td>
            <td><?= date("jS M, Y", strtotime($global_setting->created_on));?></td>
        </tr>  
    </tbody>
</table>