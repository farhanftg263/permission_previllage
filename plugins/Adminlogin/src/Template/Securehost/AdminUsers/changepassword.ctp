<?php $this->assign('title', $title);?>
<?php $this->assign('heading', $heading);?>
<?php
//$this->Breadcrumbs->addCrumb('User List', ['controller' => 'Users', 'action' => 'index']);
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
                'url' => ['controller' => 'AdminUsers', 'action' => 'changepassword'],
                'type' => 'post',
                'name'  => 'changepasswordForm',
                'id' => 'basicBootstrapForm'
            ]
        );
        ?>
        <?php
        echo $this->Form->control(
            'current_password',
            [
                'required' => false,
                'type' => 'password',
                'label' => 'Current Password',
                'placeholder' =>  'Current Password',
                'class' =>  'form-control',       
                'id' => 'current_password',
                'onchange' => "return trim(this)"
            ]
        );
        ?>
        <?php
        echo $this->Form->control(
            'new_password',
            [
                'required' => false,
                'type' => 'password',
                'label' => 'New Password',
                'placeholder' =>  'New Password',
                'class' =>  'form-control',       
                'id' => 'new_password',
                'onchange' => "return trim(this)"
            ]
        );
        ?>
        <?php
        echo $this->Form->control(
            'confirm_new_password',
            [
                'required' => false,
                'type' => 'password',
                'label' => 'Confirm New Password',
                'placeholder' =>  'Confirm New Password',
                'class' =>  'form-control',       
                'id' => 'confirm_new_password',
                'onchange' => "return trim(this)"
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