<?php $this->assign('title', $title);?>
<?php $this->assign('heading', $heading);?>
<?php
$this->Breadcrumbs->addCrumb('Manage Users', ['controller' => 'Users', 'action' => 'index']);
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
                'url' => ['controller' => 'Users', 'action' => 'add'],
                'type' => 'post',
                'name'  => 'basicBootstrapForm',
                'id'  => 'basicBootstrapForm'
            ]
        );
        ?>
        <?php
        echo $this->Form->control(
            'nickname',
            [
                'required' => false,
                'type' => 'text',
                'label' => 'Name',
                'placeholder' =>  'Name',
                'class' =>  'form-control',       
                'value' => '',
                'id' => 'nickname',
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
                'label' => 'Email',
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
            'phone',
            [
                'required' => false,
                'type' => 'text',
                'label' => 'Contact Number',
                'placeholder' =>  'Contact Number',
                'class' =>  'form-control',       
                'id' => 'contact_number',
                'onchange' => "return trim(this)"
            ]
        );
        ?>
        <?php
        $options = array(GUEST => 'Guest',HOST => 'Host');
        echo $this->Form->input('role_id', [
                'templates' => [
                    'radioWrapper' => '<div class="radio-inline screen-center screen-radio">{{label}}</div>'
                ],
                'type' => 'radio',
                'options' => $options,
                'value' => GUEST,
                'label' => false
            ]);
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

