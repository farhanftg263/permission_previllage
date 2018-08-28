<?php $this->assign('title', $title);?>
<?php $this->assign('heading', $heading);?>
<?php
$this->Breadcrumbs->addCrumb('Email Template List', ['controller' => 'EmailTemplates', 'action' => 'index']);
$this->Breadcrumbs->addCrumb($heading);
?>

<div class="row">
    <?php    
        $this->Form->templates([
            'inputContainer' => '<div class="form-group">{{content}}</div>'
        ]);
        echo $this->Form->create(
            $emailTemplate, 
            [
                'url' => ['controller' => 'EmailTemplates', 'action' => 'edit'],
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
            'slug',
            [
                'required' => false,
                'type' => 'text',
                'label' => 'Slug<sup style="color: #ce340b;">*</sup>',
                'placeholder' =>  'Slug',
                'class' =>  'form-control',       
                'id' => 'slug',
                'onchange' => "return trim(this)",
                'escape'=>false
            ]
        );
        ?>
        <?php
        echo $this->Form->control(
            'title',
            [
                'required' => false,
                'type' => 'text',
                'label' => 'Page Title<sup style="color: #ce340b;">*</sup>',
                'placeholder' =>  'Page Title',
                'class' =>  'form-control',       
                'id' => 'title',
                'onchange' => "return trim(this)",
                'escape'=>false
            ]
        );
        ?>
         <?php
        echo $this->Form->control(
            'subject',
            [
                'required' => false,
                'type' => 'text',
                'label' => 'Subject<sup style="color: #ce340b;">*</sup>',
                'placeholder' =>  'Subject',
                'class' =>  'form-control',       
                'id' => 'subject',
                'onchange' => "return trim(this)",
                'escape'=>false
            ]
        );
        ?>        
    </div>
    <div class="col-md-10">
        <?php
        echo $this->Form->control(
            'message',
            [
                'required' => false,
                'type' => 'textarea',
                'rows' => '2',
                'label' => 'Message<sup style="color: #ce340b;">*</sup>',
                'placeholder' =>  'Message ...',
                'class' =>  'form-control textarea',       
                'id' => 'message',
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
