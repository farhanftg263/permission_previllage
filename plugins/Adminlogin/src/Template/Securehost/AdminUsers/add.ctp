<?php $this->assign('title', $title);?>
<?php $this->assign('heading', $heading);?>
<?php
$this->Breadcrumbs->addCrumb('Admin User List', ['controller' => 'AdminUsers', 'action' => 'index']);
$this->Breadcrumbs->addCrumb($heading);
?>

<div class="row">    
    <div class="col-md-6">
        <?= $this->Flash->render();?>
        <?php    
        $this->Form->templates([
            'inputContainer' => '<div class="form-group">{{content}}</div>'
        ]);
        echo $this->Form->create(
            $user, 
            [
                'url' => ['controller' => 'AdminUsers', 'action' => 'add'],
                'type' => 'post',
                'name'  => 'basicBootstrapForm',
                'id'  => 'basicBootstrapForm'
            ]
        );
        ?>
        <?php
        echo $this->Form->control(
            'first_name',
            [
                'required' => false,
                'type' => 'text',
                'label' => 'First Name',
                'placeholder' =>  'First Name',
                'class' =>  'form-control',       
                'id' => 'first_name',
                'onchange' => "return trim(this)",
                'maxlength' => 30
            ]
        );
        ?>
        <?php
        echo $this->Form->control(
            'last_name',
            [
                'required' => false,
                'type' => 'text',
                'label' => 'Last Name',
                'placeholder' =>  'Last Name',
                'class' =>  'form-control',       
                'id' => 'last_name',
                'onchange' => "return trim(this)",
                'maxlength' => 30
            ]
        );
        ?>
        <?php
        echo $this->Form->control(
            'email',
            [
                'required' => false,
                'type' => 'email',
                'label' => 'Email Address',
                'placeholder' =>  'Email Address',
                'class' =>  'form-control',       
                'id' => 'email',
                'onchange' => "return trim(this)",
                'maxlength' => 100
            ]
        );
        ?>
       
        <?php
        echo $this->Form->control(
            'username',
            [
                'required' => false,
                'type' => 'text',
                'label' => 'Username',
                'placeholder' =>  'Username',
                'class' =>  'form-control',       
                'id' => 'username',
                'onchange' => "return trim(this)",
                'maxlength' => 20
            ]
        );
        ?>
        <?php
        echo $this->Form->control(
            'password',
            [
                'required' => false,
                'type' => 'password',
                'label' => 'Password',
                'placeholder' =>  'Password',
                'class' =>  'form-control',       
                'id' => 'password',
                'onchange' => "return trim(this)",
                'maxlength' => 30
            ]
        );
        ?>
        <?php
        echo $this->Form->control(
            'role_id',
            [
                'required' => false,
                'type' => 'select',
                'label' => 'Role',
                'placeholder' =>  'Role',
                'class' =>  'form-control select2',       
                'id' => 'role_id',
                'style' => 'width: 100%;',
                'options' => $roles,
                'empty' => '-- select --'
            ]
        );
        ?>
       
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
</div>

