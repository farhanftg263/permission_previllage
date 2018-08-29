<?= $this->assign('title', $title);?>
<?= $this->assign('heading', $heading);?>
<?php
$this->Html->addCrumb('Admin Role List', ['controller' => 'Roles', 'action' => 'adminIndex']);
$this->Html->addCrumb($heading);
?>
<?php //echo $this->Html->css('formvalidation.bootstrap.min'); ?>
<div class="row">    
    <div class="col-md-6">
        <?= $this->Flash->render();?>
        <?php    
        $this->Form->templates([
            'inputContainer' => '<div class="form-group">{{content}}</div>'
        ]);
        echo $this->Form->create(
            $role, 
            [
                'url' => ['controller' => 'Roles', 'action' => 'admin_add'],
                'type' => 'post',
                'name'  => 'roleForm',
                'id' => 'basicBootstrapForm'
            ]
        );
        ?>
        <?= $this->Form->hidden('admin',['value' => true]); ?>
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
                'autocomplete'=>'off',
                'type' => 'textarea',
                'rows' => '2',
                'label' => 'Description',
                'placeholder' =>  'Description ...',
                'class' =>  'form-control',       
                'id' => 'description',
                'style' => 'width: 100%;',
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
<?= $this->Html->script('formValidation.min',['type'=>"text/javascript"]);?>
<?= $this->Html->script('formvalidation.bootstrap.min',['type'=>"text/javascript"]);?>
<script type="text/javascript">
$(document).ready(function() {
    $('#basicBootstrapForm')
        .on('init.field.fv', function(e, data) {
            var $parent = data.element.parents('.form-group'),
                $icon   = $parent.find('.form-control-feedback[data-fv-icon-for="' + data.field + '"]');
            $icon.on('click.clearing', function() {
                if ($icon.hasClass('glyphicon-remove')) {
                    data.fv.resetField(data.element);
                }
            });
        })
        .formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                name: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_roles_vars['RoleError']['name']['ER001'];?>'
                        },
                        stringLength: {
                            min: 2,
                            max: 30,
                            message: '<?= $lang_roles_vars['RoleError']['name']['ER002'];?>'
                        },
                        regexp: {
                            regexp: /^[A-Za-z_0-9\.\-']+( [A-Za-z_0-9\.\-']+)*$/,
                            message: '<?= $lang_roles_vars['RoleError']['name']['ER003'];?>'
                        },
                        remote: {
                            url: '<?= $this->Url->build(['controller' => 'Roles', 'action' => 'adminAdd'], true);?>',
                            data: function(validator, $field, value) {
                                return {
                                    action:'validate',
                                    type:'role_name',
                                    name: validator.getFieldElements('name').val()
                                };
                            },
                            message: '<?= $lang_roles_vars['RoleError']['name']['ER004'];?>',
                            type: 'POST',
                            //delay: 2000
                        },       
                    }
                },
                description: {
                    threshold: 2,
                    verbose:false,
                    validators: {
                        stringLength: {
                            min: 2,
                            max: 255,
                            message: '<?= $lang_roles_vars['RoleError']['description']['ER001'];?>'
                        },
                    }
                },
            }
    });
});
</script>