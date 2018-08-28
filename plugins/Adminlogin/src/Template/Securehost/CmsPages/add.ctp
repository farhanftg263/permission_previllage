<?php $this->assign('title', $title);?>
<?php $this->assign('heading', $heading);?>
<?php
$this->Breadcrumbs->addCrumb('CMS Page List', ['controller' => 'CmsPages', 'action' => 'index']);
$this->Breadcrumbs->addCrumb($heading);
?>

<div class="row">
    <?php    
        $this->Form->templates([
            'inputContainer' => '<div class="form-group">{{content}}</div>'
        ]);
        echo $this->Form->create(
            $cms_page, 
            [
                'url' => ['controller' => 'CmsPages', 'action' => 'add'],
                'type' => 'file',
                'name'  => 'basicBootstrapForm',
                'id'  => 'basicBootstrapForm'
            ]
        );
        ?>
    <div class="col-md-6">
        <?= $this->Flash->render();?>
        
        <?php
        echo $this->Form->control(
            'page_name',
            [
                'required' => false,
                'type' => 'text',
                'label' => 'Page Name<sup style="color: #ce340b;">*</sup>',
                'placeholder' =>  'Page Name',
                'class' =>  'form-control',       
                'id' => 'page_name',
                'onchange' => "return trim(this)",
                'escape'=>false
            ]
        );
        ?>
        <?php
        echo $this->Form->control(
            'page_title',
            [
                'required' => false,
                'type' => 'text',
                'label' => 'Meta Title<sup style="color: #ce340b;">*</sup>',
                'placeholder' =>  'Meta Title',
                'class' =>  'form-control',       
                'id' => 'title',
                'onchange' => "return trim(this)",
                 'escape'=>false
            ]
        );
        ?>     
        <?php
        echo $this->Form->control(
            'meta_keyword',
            [
                'required' => false,
                'type' => 'textarea',
                'rows' => '2',
                'label' => 'Meta Keyword',
                'placeholder' =>  'Meta Keyword ...',
                'class' =>  'form-control textarea',       
                'id' => 'meta_keyword',
                'style' => 'width: 100%;',
                'onchange' => "return trim(this)"
            ]
        );
        ?>
        <?php
        echo $this->Form->control(
            'meta_description',
            [
                'required' => false,
                'type' => 'textarea',
                'rows' => '2',
                'label' => 'Meta Description',
                'placeholder' =>  'Meta Description ...',
                'class' =>  'form-control textarea',       
                'id' => 'meta_description',
                'style' => 'width: 100%;',
                'onchange' => "return trim(this)"
            ]
        );
        ?>
        <?php
        echo $this->Form->control(
            'page_slug',
            [
                'required' => false,
                'type' => 'text',
                'label' => 'Page Slug<sup style="color: #ce340b;">*</sup>',
                'placeholder' =>  'Page Slug',
                'class' =>  'form-control',       
                'id' => 'page_slug',
                'onchange' => "return trim(this)",
                'escape'=>false
            ]
        );
        ?>
    </div>
    <div class="col-md-10">
        <?php
        echo $this->Form->control(
            'page_content',
            [
                'required' => false,
                'type' => 'textarea',
                'rows' => '2',
                'label' => 'Page Content<sup style="color: #ce340b;">*</sup>',
                'placeholder' =>  'Page Content ...',
                'class' =>  'form-control textarea',       
                'id' => 'page_content',
                'style' => 'width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;',
                'onchange' => "return trim(this)",
                 'escape'=>false
            ]
        );
        ?>
        <div class="form-group">
        <?php
        $sizes = ['1' => 'Active', '0' => 'Inactive',];
        echo $this->Form->select(
            'status',
            $sizes,
            [
                'default' => '1',
                'class' => 'form-control select2',
                'style' => 'width: 150px;',
            ]
        );
        ?>
        </div>
        <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-primary']) ?>
        
        <span style="margin-left:73px;">
        <?php
        echo $this->Form->button(
            'Submit',
            [
                'type' => 'submit',
                'class' =>  'btn btn-primary',
            ]
        );
        ?>
        </span>    
    </div>
        <?php echo $this->Form->end();?>
    </div>
</div>
