<?php $this->assign('title', $title);?>
<p class="login-box-msg"><?= __('Forgot Password') ?></p>
<p> <?= $this->Flash->render(); ?> </p>
<p> <?= $this->Flash->render('auth'); ?> </p>
<?php    
echo $this->Form->create(
    null, 
    [
        'url' => ['controller' => 'AdminUsers', 'action' => 'forgotpassword'],
        'type' => 'post',
        'name' => 'basicBootstrapForm',
        'id' => 'basicBootstrapForm'
    ]
);
?>
<div class="form-group has-feedback">
    <?php
    echo $this->Form->control(
        'email',
        [
            'required' => false,
            'label' => false,
            'placeholder'   =>  'Registered E-mail',
            'class' =>  'form-control',                
        ]
    );
    ?>
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
</div>
<div class="row">
  <div class="col-xs-8">
<!--        <div class="checkbox icheck">
      <label>
        <input type="checkbox"> Remember Me
      </label>
    </div>-->
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