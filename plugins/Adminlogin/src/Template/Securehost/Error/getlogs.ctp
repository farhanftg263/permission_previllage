<?= $this->assign('title', $title);?>
<?= $this->assign('heading', $heading);?>
<?php
$this->Html->addCrumb($heading);
?>

<div class="box-body">
    <table id="example1" class="table table-bordered table-striped">
        <?php if(empty($logs)){?>
        <tbody>
            <tr>
                <td colspan="6" align="center" class="error">No record(s) found!</td>
            </tr>
        </tbody>
        <?php }else{?>
        <thead>
            <tr>
                <th width="5%">S.N.</th>
                <th width="20%">Message</th>
                <th width="25%">File</th>
                <th width="5%">Line</th>
                <th width="15%">Browser</th>
                <th width="20%">Referer</th>
                <th><?= $this->Paginator->sort('created', 'Date');?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i=0; foreach($logs as $error):$i++?>
            <tr>
                <td><?= $i;?></td>
                <td><?= $error->message;?></td>
                <td><?= $error->file;?></td>
                <td><?= $error->line;?></td>
                <td><?= $error->browser;?></td>
                <td><?= $error->referer;?></td>
                <td><?= date("jS M, Y",strtotime($error->created));?></td>
            </tr>
            <?php endforeach;?>
        </tbody>
        <?php }?>
    </table>
    <?php if(!empty($logs)){?>
    <div class="box-footer">                    
        <div class="col-sm-5">
            <?php 
            $total_records = ($this->Paginator->params()['count']);
            $total_pages  =  $this->Paginator->counter(array('format'=>'%pages%')); 
            $start = $this->Paginator->counter(array('format' => '%start%')); 
            $current_page =  $this->Paginator->counter(array('format'=>'%page%')); 
            $options_pages ='';
            ?>
        </div>
        <div class="col-sm-7">
            <div id="example1_paginate" class="dataTables_paginate paging_simple_numbers" style="float:right;">
            <?php 
            $total = $this->Paginator->counter('{{pages}}');
            if($total > 1) { ?>
                <ul class="pagination">
                    <li id="example1_previous" class="paginate_button previous disabled"><?php echo $this->Paginator->first(' First ',['class' => 'disabled']);?></li>
                    <li id="example1_previous" class="paginate_button previous disabled"><?php echo $this->Paginator->prev(' Previous ',['class' => 'disabled']);?></li>
                    <?php echo $this->Paginator->numbers();?>
                    <li id="example1_next" class="paginate_button next disabled"><?php echo $this->Paginator->next(' Next ',['class' => 'disabled']);?></li> 
                    <li id="example1_previous" class="paginate_button previous disabled"><?php echo $this->Paginator->last(' Last ',['class' => 'disabled']);?></li>
                </ul>    
            <?php } ?>
            </div>
        </div>
    </div>
    <?php }?>
</div>    