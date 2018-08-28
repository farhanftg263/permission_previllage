<?php $this->assign('title', $title);?>
<p class="login-box-msg"><?= __('Reset Password') ?></p>
<p> <?= $this->Flash->render(); ?> </p>
<?=
$this->Form->create(null, [
    'type' => 'post',
    'name' => 'frmResetpassword',
    'id' => 'basicBootstrapForm',
])
?>
<div class="form-group has-feedback">
    <?php
    echo $this->Form->control(
        'new_password',
        [
            'required' => false,
            'label' => false,
            'placeholder'   =>  'New Password',
            'class' =>  'form-control', 
            'type'  =>  'password',
        ]
    );
    ?>
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
</div>
<div class="form-group has-feedback">
    <?php
    echo $this->Form->control(
        'confirm_new_password',
        [
            'required' => false,
            'label' => false,
            'placeholder'   =>  'Confirm New Password',
            'class' =>  'form-control',
            'type'  =>  'password',
        ]
    );
    ?>
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
</div>
<div class="row">
  <div class="col-xs-8">
  </div>
  <!-- /.col -->
    <div class="col-xs-4">
        <?php
        echo $this->Form->button(
            'Submit',
            [
                'type' => 'submit',
                'class' =>  'btn btn-primary btn-block btn-flat',
            ]
        );
        ?>
    </div>
  <!-- /.col -->
</div>
<?php echo $this->Form->end();?>
<?= $this->Html->link('Back To Login',['controller' => 'AdminUsers', 'action' => 'login'],['escape' => false]);?>
<br>