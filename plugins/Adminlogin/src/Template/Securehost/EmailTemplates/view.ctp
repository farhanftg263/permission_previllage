<?php $this->assign('title', $title);?>
<?php $this->assign('heading', $heading);?>
                        
<table id="example1" class="table table-bordered table-striped">
    <tbody>
        <tr>
            <td width="15%">Slug</td>
            <td width="2%">:</td>
            <td width="83%"><?= $email_template->slug;?></td>
        </tr>
        <tr>
            <td>Title</td>
            <td>:</td>
            <td><?= $email_template->title;?></td>
        </tr>
        <tr>
            <td>Subject</td>
            <td>:</td>
            <td><?= $email_template->subject;?></td>
        </tr>        
        <tr>
            <td>Message</td>
            <td>:</td>
            <td><?= html_entity_decode($email_template->message);?></td>
        </tr>        
        <tr>
            <td>Status</td>
            <td>:</td>
            <td><?= ($email_template->status==1)?'Active':'Inactive';?></td>
        </tr>  
        <tr>
            <td>Created</td>
            <td>:</td>
            <td><?= date("jS M, Y", strtotime($email_template->created_on));?></td>
        </tr>  
    </tbody>
</table>