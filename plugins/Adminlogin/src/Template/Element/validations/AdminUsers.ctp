<?php
if(in_array($this->request->action, ['add', 'edit'])){
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#basicBootstrapForm').on('init.field.fv', function (e, data) {
            var $parent = data.element.parents('.form-group'),
                    $icon = $parent.find('.form-control-feedback[data-fv-icon-for="' + data.field + '"]');
            $icon.on('click.clearing', function () {
                if ($icon.hasClass('glyphicon-remove')) {
                    data.fv.resetField(data.element);
                }
            });
        })
        $('#basicBootstrapForm').formValidation({
            framework: 'bootstrap',
            excluded: [':disabled'],
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {                
                first_name: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_users_vars['Error']['first_name']['ER001'];?>'
                        },
                        stringLength: {
                            min: 2,
                            max: 30,
                            message: '<?= $lang_users_vars['Error']['first_name']['ER002'];?>'
                        },
                        regexp: {
                            regexp: /^[A-Za-z_0-9\.\-']+( [A-Za-z_0-9\.\-']+)*$/,
                            message: '<?= $lang_users_vars['Error']['first_name']['ER003'];?>'
                        },      
                    }
                },
                last_name: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_users_vars['Error']['last_name']['ER001'];?>'
                        },
                        stringLength: {
                            min: 2,
                            max: 30,
                            message: '<?= $lang_users_vars['Error']['last_name']['ER002'];?>'
                        },
                        regexp: {
                            regexp: /^[A-Za-z_0-9\.\-']+( [A-Za-z_0-9\.\-']+)*$/,
                            message: '<?= $lang_users_vars['Error']['last_name']['ER003'];?>'
                        },      
                    }
                },
                email: {
                    verbose: false,
                    threshold: 30,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_users_vars['Error']['email']['ER001'];?>'
                        },  
                        emailAddress: {
                            message: '<?= $lang_users_vars['Error']['email']['ER002'];?>'
                        },
                        stringLength: {
                            min: 7,
                            max: 50,
                            message: '<?= $lang_users_vars['Error']['email']['ER003'];?>'
                        }
                    }               
                },
                username: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_users_vars['Error']['username']['ER001'];?>'
                        },                    
                        regexp: {
                            regexp: /^\S*$/,
                            message: '<?= $lang_users_vars['Error']['username']['ER002'];?>'
                        },
                        stringLength: {
                            min: 5,
                            max: 20,
                            message: '<?= $lang_users_vars['Error']['username']['ER003'];?>'
                        }
                    }
                },
                password: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_users_vars['Error']['password']['ER001'];?>'
                        },                    
                        regexp: {
                            regexp: /^\S*$/,
                            message: '<?= $lang_users_vars['Error']['password']['ER002'];?>'
                        },
                        stringLength: {
                            min: 5,
                            max: 30,
                            message: '<?= $lang_users_vars['Error']['password']['ER003'];?>'
                        },
                    }
                },  
                role_id: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_users_vars['Error']['role_id']['ER001'];?>'
                        }, 
                    }
                },
                about_me: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        stringLength: {
                            min: 2,
                            max: 255,
                            message: '<?= $lang_users_vars['Error']['about_me']['ER001'];?>'
                        },
                    }
                },
            }

        });


    });
</script>
<?php }elseif($this->request->action == 'login'){?>
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
                username: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_users_vars['Error']['username']['ER001'];?>'
                        },                    
                        regexp: {
                            regexp: /^\S*$/,
                            message: '<?= $lang_users_vars['Error']['username']['ER002'];?>'
                        },
                        stringLength: {
                            min: 5,
                            max: 20,
                            message: '<?= $lang_users_vars['Error']['username']['ER003'];?>'
                        },
                    }
                },
                password: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_users_vars['Error']['password']['ER001'];?>'
                        },                    
                        regexp: {
                            regexp: /^\S*$/,
                            message: '<?= $lang_users_vars['Error']['password']['ER002'];?>'
                        },
                        stringLength: {
                            min: 5,
                            max: 30,
                            message: '<?= $lang_users_vars['Error']['password']['ER003'];?>'
                        },
                    }
                }, 
            }
    });
});
</script>
<?php }elseif($this->request->action == 'forgotpassword'){?>
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
                email: {
                    verbose: false,
                    threshold: 30,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_users_vars['Error']['email']['ER001'];?>'
                        },  
                        emailAddress: {
                            message: '<?= $lang_users_vars['Error']['email']['ER002'];?>'
                        },
                        stringLength: {
                            min: 7,
                            max: 50,
                            message: '<?= $lang_users_vars['Error']['email']['ER003'];?>'
                        },
                    }               
                },
            }
    });
});
</script>
<?php }elseif($this->request->action == 'reset'){?>
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
                new_password: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_users_vars['Error']['new_password']['ER001'];?>'
                        },                    
                        regexp: {
                            regexp: /^\S*$/,
                            message: '<?= $lang_users_vars['Error']['new_password']['ER002'];?>'
                        },
                        stringLength: {
                            min: 5,
                            max: 30,
                            message: '<?= $lang_users_vars['Error']['new_password']['ER003'];?>'
                        },
                    }
                }, 
                confirm_new_password: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_users_vars['Error']['new_password']['ER001'];?>'
                        },                    
                        regexp: {
                            regexp: /^\S*$/,
                            message: '<?= $lang_users_vars['Error']['new_password']['ER002'];?>'
                        },
                        stringLength: {
                            min: 5,
                            max: 30,
                            message: '<?= $lang_users_vars['Error']['new_password']['ER003'];?>'
                        },
                        identical: {
                            field: 'new_password',
                            message: '<?= $lang_users_vars['Error']['new_password']['ER006'];?>'
                        }
                    }
                }, 
            }
    });
});
</script>
<?php }elseif($this->request->action == 'changepassword'){?>
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
                current_password: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_users_vars['Error']['current_password']['ER001'];?>'
                        },                    
                        regexp: {
                            regexp: /^\S*$/,
                            message: '<?= $lang_users_vars['Error']['current_password']['ER002'];?>'
                        },
                        stringLength: {
                            min: 5,
                            max: 30,
                            message: '<?= $lang_users_vars['Error']['current_password']['ER003'];?>'
                        },
                        
                    }
                }, 
                new_password: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_users_vars['Error']['new_password']['ER001'];?>'
                        },                    
                        regexp: {
                            regexp: /^\S*$/,
                            message: '<?= $lang_users_vars['Error']['new_password']['ER002'];?>'
                        },
                        stringLength: {
                            min: 5,
                            max: 30,
                            message: '<?= $lang_users_vars['Error']['new_password']['ER003'];?>'
                        },
                    }
                }, 
                confirm_new_password: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_users_vars['Error']['confirm_new_password']['ER001'];?>'
                        },                    
                        regexp: {
                            regexp: /^\S*$/,
                            message: '<?= $lang_users_vars['Error']['confirm_new_password']['ER002'];?>'
                        },
                        stringLength: {
                            min: 5,
                            max: 30,
                            message: '<?= $lang_users_vars['Error']['confirm_new_password']['ER003'];?>'
                        },
                        identical: {
                            field: 'new_password',
                            message: '<?= $lang_users_vars['Error']['confirm_new_password']['ER004'];?>'
                        }
                    }
                }, 
            }
    });
});
</script>
<?php }?>
