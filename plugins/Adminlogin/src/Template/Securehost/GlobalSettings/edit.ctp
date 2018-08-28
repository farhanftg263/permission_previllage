<?php $this->assign('title', $title);?>
<?php $this->assign('heading', $heading);?>
<?php
$this->Breadcrumbs->addCrumb('Global Setting List', ['controller' => 'GlobalSettings', 'action' => 'index']);
$this->Breadcrumbs->addCrumb($heading);
?>

<div class="row">
    <?php    
        $this->Form->templates([
            'inputContainer' => '<div class="form-group">{{content}}</div>'
        ]);
        echo $this->Form->create(
            $globalSetting, 
            [
                'url' => ['controller' => 'GlobalSettings', 'action' => 'edit'],
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
            'reference',
            [
                'required' => false,
                'type' => 'text',
                'label' => 'Reference<sup style="color: #ce340b;">*</sup>',
                'placeholder' =>  'Reference',
                'class' =>  'form-control',       
                'id' => 'reference',
                'onchange' => "return trim(this)",
                'escape'=>false
            ]
        );
        ?>
        <?php
        echo $this->Form->control(
            'datatype',
            [
                'required' => false,
                'type' => 'text',
                'label' => 'Type<sup style="color: #ce340b;">*</sup>',
                'placeholder' =>  'Type',
                'class' =>  'form-control',       
                'id' => 'datatype',
                'onchange' => "return trim(this)",
                'escape'=>false
            ]
        );
        ?> 
        <?php
        echo $this->Form->control(
            'value',
            [
                'required' => false,
                'type' => 'text',
                'label' => 'Value<sup style="color: #ce340b;">*</sup>',
                'placeholder' =>  'Value',
                'class' =>  'form-control',       
                'id' => 'value',
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
