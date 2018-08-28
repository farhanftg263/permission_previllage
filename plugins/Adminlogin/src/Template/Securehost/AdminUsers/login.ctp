<?php $this->assign('title', $title);?>
<p class="login-box-msg"><strong><?= __($title) ?>:</strong> <?= __('Secure Administration Suite')?></p>
<div id="message_success">
<p> <?= $this->Flash->render(); ?> </p>
<p> <?= $this->Flash->render('auth'); ?> </p>
</div>
<?php 
echo $this->Form->create(
    null, 
    [
        'url' => ['controller' => 'AdminUsers', 'action' => 'login'],
        'type' => 'post',
        'id' => 'basicBootstrapForm'
    ]
);
?>
<div class="form-group has-feedback">
    <?php
    echo $this->Form->control(
        'username',
        [
            'required' => false,
            'label' => false,
            'placeholder'   =>  'Username',
            'class' =>  'form-control',                
        ]
    );
    ?>
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
</div>
<div class="form-group has-feedback">
    <?php
    echo $this->Form->control(
        'password',
        [
            'required' => false,
            'label' => false,
            'placeholder'   =>  'Password',
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
            'Login',
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
<?= $this->Html->link('Forgot Password',['controller' => 'AdminUsers', 'action' => 'forgotpassword'],['escape' => false]);?>
<br>

