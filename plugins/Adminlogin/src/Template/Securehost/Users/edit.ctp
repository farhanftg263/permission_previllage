<?php $this->assign('title', $title); ?>
<?php $this->assign('heading', $heading); ?>
<?php
$this->Breadcrumbs->addCrumb('Manage Users', ['controller' => 'Users', 'action' => 'index']);
$this->Breadcrumbs->addCrumb($heading);
?>

<div class="row">    
    <div class="col-md-6">
        <?= $this->Flash->render(); ?>
        <?php
        $this->Form->templates([
            'inputContainer' => '<div class="form-group">{{content}}</div>'
        ]);
        echo $this->Form->create(
                $user, [
            'url' => ['controller' => 'Users', 'action' => 'edit'],
            'type' => 'post',
            'name' => 'basicBootstrapForm',
            'id' => 'basicBootstrapForm'
                ]
        );
        ?>
        <?php
        echo $this->Form->control(
                'nickname', [
            'required' => false,
            'type' => 'text',
            'label' => 'Name',
            'placeholder' => 'Name',
            'class' => 'form-control',
            'id' => 'nickname',
            'onchange' => "return trim(this)",
            'maxlength' => 30
                ]
        );
        ?>
        <?php
        echo $this->Form->control(
                'email', [
            'required' => false,
            'type' => 'email',
            'label' => 'Email',
            'placeholder' => 'Email Address',
            'class' => 'form-control',
            'id' => 'email',
            'disabled'=>'disabled',
            'onchange' => "return trim(this)",
            'maxlength' => 100
                ]
        );
        ?>

        <?php
        echo $this->Form->control(
                'phone', [
            'required' => false,
            'type' => 'text',
            'label' => 'Contact Number',
            'placeholder' => 'Contact Number',
            'class' => 'form-control',
            'id' => 'contact_number',
            'onchange' => "return trim(this)"
                ]
        );
        ?>
        <?php
        $role = 'N/A';
        $roles = array();
        $roles = array_column($user->roles, 'id');
        if (!empty($roles)) {
            if (in_array(GUEST, $roles) && in_array(HOST, $roles)) {
                $role = 'Both';
            } else {
                $role = in_array(GUEST, $roles) ? 'Guest' : 'Host';
            }
        }
        ?>
        <div class="form-group has-feedback">
            <label for="contact_number">User Profile</label>
            <span class = "form-control"><?= $role; ?></span>            
        </div>
        <?php
        echo $this->Form->button(
                'Submit', [
            'type' => 'submit',
            'class' => 'btn btn-primary',
                ]
        );
        ?>      
        <?php echo $this->Form->end(); ?>
    </div>
</div>
</div>

