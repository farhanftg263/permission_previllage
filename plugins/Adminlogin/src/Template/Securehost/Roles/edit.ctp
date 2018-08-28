<?php $this->assign('title', $title);?>
<?php $this->assign('heading', $heading);?>
<?php
$this->Breadcrumbs->addCrumb('Admin User List', ['controller' => 'AdminUsers', 'action' => 'index']);
$this->Breadcrumbs->addCrumb($heading);
?>

<div class="col-md-6">
        <?= $this->Flash->render();?>
        <?php    
        $this->Form->templates([
            'inputContainer' => '<div class="form-group">{{content}}</div>'
        ]);
        echo $this->Form->create(
            $role, 
            [
                'url' => ['controller' => 'Roles', 'action' => 'edit'],
                'type' => 'post',
                'name'  => 'roleForm',
                'id'  => 'basicBootstrapForm'
            ]
        );
        ?>
        <?php
        echo $this->Form->control(
            'name',
            [
                'required' => false,
                'type' => 'text',
                'label' => 'Role Name',
                'placeholder' =>  'Role Name',
                'class' =>  'form-control',       
                'id' => 'name',
                'autocomplete'=>'off',
                'onchange' => "return trim(this)"
            ]
        );
        ?>    
        <?php
        echo $this->Form->control(
            'description',
            [
                'required' => false,
                'type' => 'textarea',
                'rows' => '2',
                'label' => 'Description',
                'placeholder' =>  'Description ...',
                'class' =>  'form-control',       
                'id' => 'description',
                'style' => 'width: 100%;',
                'autocomplete'=>'off',
                'onchange' => "return trim(this)"
            ]
        );
        ?>
        <div class="form-group">
        <?php
        $sizes = ['1' => 'Active', '0' => 'In-Active',];
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
        <?php
        echo $this->Form->button(
            'Submit',
            [
                'type' => 'submit',
                'class' =>  'btn btn-primary',
            ]
        );
        ?>      
        <?php echo $this->Form->end();?>
    </div>
</div>

