<?php $this->assign('title', $title);?>
<?php $this->assign('heading', $heading);?>
                        
<table id="example1" class="table table-bordered table-striped">
    <tbody>
        <tr>
            <td width="18%">Page Name</td>
            <td width="2%">:</td>
            <td width="80%"><?= $cms_page->page_name;?></td>
        </tr>
        <tr>
            <td>Meta Title</td>
            <td>:</td>
            <td><?= $cms_page->page_title;?></td>
        </tr>
        <tr>
            <td>Meta Keyword</td>
            <td>:</td>
            <td><?= $cms_page->meta_keyword;?></td>
        </tr>
        <tr>
            <td>Meta Description</td>
            <td>:</td>
            <td><?= $cms_page->meta_description;?></td>
        </tr>
        <tr>
            <td>Page Content</td>
            <td>:</td>
            <td><?= html_entity_decode($cms_page->page_content);?></td>
        </tr>        
        <tr>
            <td>Status</td>
            <td>:</td>
            <td><?= ($cms_page->status==1)?'Active':'Inactive';?></td>
        </tr>  
        <tr>
            <td>Created</td>
            <td>:</td>
            <td><?= date("jS M, Y", strtotime($cms_page->created_on));?></td>
        </tr>  
    </tbody>
</table>