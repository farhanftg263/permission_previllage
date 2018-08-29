<?php
if (in_array($this->request->action, ['index'])) {
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
                    start_date: {
                        threshold: 30,
                        verbose: false,
                        validators: {
                            notEmpty: {
                                message: 'Start date is required'
                            },
                            date: {
                                format: 'DD-MM-YYYY',
                                message: 'The date is not a valid'
                            }
                        }
                    },
                    end_date: {
                        threshold: 30,
                        verbose: false,
                        validators: {
                            notEmpty: {
                                message: 'End date is required'
                            }
                        }
                    },
                    package_type: {
                        threshold: 30,
                        verbose: false,
                        validators: {
                            notEmpty: {
                                message: 'Package type is required'
                            }
                        }
                    }

                }

            }).find('#start_date')
                    .datepicker({
                        onSelect: function (dateText, inst) {
                            /* Revalidate the field when choosing it from the datepicker */
                            $('#basicBootstrapForm').formValidation('revalidateField', 'start_date');
                            var minDate = $('#start_date').datepicker('getDate');
                            $("#end_date").datepicker("change", {minDate: minDate});

                        },
                        dateFormat: "dd-mm-yy",
                        maxDate: new Date(),
                        changeMonth: true,
                        changeYear: true
                    }).end().find('#end_date')
                    .datepicker({
                        onSelect: function (date, inst) {
                            /* Revalidate the field when choosing it from the datepicker */
                            var maxDate = $('#end_date').datepicker('getDate');
                            $("#start_date").datepicker("change", {maxDate: maxDate});
                            $('#basicBootstrapForm').formValidation('revalidateField', 'end_date');
                        },
                        dateFormat: "dd-mm-yy",
                        maxDate: new Date(),
                        changeMonth: true,
                        changeYear: true
                    });


        });
    </script>
<?php } elseif ($this->request->action == 'login') { ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#basicBootstrapForm')
                    .on('init.field.fv', function (e, data) {
                        var $parent = data.element.parents('.form-group'),
                                $icon = $parent.find('.form-control-feedback[data-fv-icon-for="' + data.field + '"]');
                        $icon.on('click.clearing', function () {
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
                                verbose: false,
                                validators: {
                                    notEmpty: {
                                        message: '<?= $lang_users_vars['Error']['username']['ER001']; ?>'
                                    },
                                    regexp: {
                                        regexp: /^\S*$/,
                                        message: '<?= $lang_users_vars['Error']['username']['ER002']; ?>'
                                    },
                                    stringLength: {
                                        min: 5,
                                        max: 20,
                                        message: '<?= $lang_users_vars['Error']['username']['ER003']; ?>'
                                    },
                                }
                            },
                            password: {
                                threshold: 30,
                                verbose: false,
                                validators: {
                                    notEmpty: {
                                        message: '<?= $lang_users_vars['Error']['password']['ER001']; ?>'
                                    },
                                    regexp: {
                                        regexp: /^\S*$/,
                                        message: '<?= $lang_users_vars['Error']['password']['ER002']; ?>'
                                    },
                                    stringLength: {
                                        min: 5,
                                        max: 30,
                                        message: '<?= $lang_users_vars['Error']['password']['ER003']; ?>'
                                    },
                                }
                            },
                        }
                    });
        });
    </script>
<?php } elseif ($this->request->action == 'forgotpassword') { ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#basicBootstrapForm')
                    .on('init.field.fv', function (e, data) {
                        var $parent = data.element.parents('.form-group'),
                                $icon = $parent.find('.form-control-feedback[data-fv-icon-for="' + data.field + '"]');
                        $icon.on('click.clearing', function () {
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
                                        message: '<?= $lang_users_vars['Error']['email']['ER001']; ?>'
                                    },
                                    emailAddress: {
                                        message: '<?= $lang_users_vars['Error']['email']['ER002']; ?>'
                                    },
                                    stringLength: {
                                        min: 7,
                                        max: 50,
                                        message: '<?= $lang_users_vars['Error']['email']['ER003']; ?>'
                                    },
                                }
                            },
                        }
                    });
        });
    </script>
<?php } elseif ($this->request->action == 'reset') { ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#basicBootstrapForm')
                    .on('init.field.fv', function (e, data) {
                        var $parent = data.element.parents('.form-group'),
                                $icon = $parent.find('.form-control-feedback[data-fv-icon-for="' + data.field + '"]');
                        $icon.on('click.clearing', function () {
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
                                verbose: false,
                                validators: {
                                    notEmpty: {
                                        message: '<?= $lang_users_vars['Error']['new_password']['ER001']; ?>'
                                    },
                                    regexp: {
                                        regexp: /^\S*$/,
                                        message: '<?= $lang_users_vars['Error']['new_password']['ER002']; ?>'
                                    },
                                    stringLength: {
                                        min: 5,
                                        max: 30,
                                        message: '<?= $lang_users_vars['Error']['new_password']['ER003']; ?>'
                                    },
                                }
                            },
                            confirm_new_password: {
                                threshold: 30,
                                verbose: false,
                                validators: {
                                    notEmpty: {
                                        message: '<?= $lang_users_vars['Error']['new_password']['ER001']; ?>'
                                    },
                                    regexp: {
                                        regexp: /^\S*$/,
                                        message: '<?= $lang_users_vars['Error']['new_password']['ER002']; ?>'
                                    },
                                    stringLength: {
                                        min: 5,
                                        max: 30,
                                        message: '<?= $lang_users_vars['Error']['new_password']['ER003']; ?>'
                                    },
                                    identical: {
                                        field: 'new_password',
                                        message: '<?= $lang_users_vars['Error']['new_password']['ER006']; ?>'
                                    }
                                }
                            },
                        }
                    });
        });
    </script>
<?php } elseif ($this->request->action == 'changepassword') { ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#basicBootstrapForm')
                    .on('init.field.fv', function (e, data) {
                        var $parent = data.element.parents('.form-group'),
                                $icon = $parent.find('.form-control-feedback[data-fv-icon-for="' + data.field + '"]');
                        $icon.on('click.clearing', function () {
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
                                verbose: false,
                                validators: {
                                    notEmpty: {
                                        message: '<?= $lang_users_vars['Error']['current_password']['ER001']; ?>'
                                    },
                                    regexp: {
                                        regexp: /^\S*$/,
                                        message: '<?= $lang_users_vars['Error']['current_password']['ER002']; ?>'
                                    },
                                    stringLength: {
                                        min: 5,
                                        max: 30,
                                        message: '<?= $lang_users_vars['Error']['current_password']['ER003']; ?>'
                                    },
                                }
                            },
                            new_password: {
                                threshold: 30,
                                verbose: false,
                                validators: {
                                    notEmpty: {
                                        message: '<?= $lang_users_vars['Error']['new_password']['ER001']; ?>'
                                    },
                                    regexp: {
                                        regexp: /^\S*$/,
                                        message: '<?= $lang_users_vars['Error']['new_password']['ER002']; ?>'
                                    },
                                    stringLength: {
                                        min: 5,
                                        max: 30,
                                        message: '<?= $lang_users_vars['Error']['new_password']['ER003']; ?>'
                                    },
                                }
                            },
                            confirm_new_password: {
                                threshold: 30,
                                verbose: false,
                                validators: {
                                    notEmpty: {
                                        message: '<?= $lang_users_vars['Error']['confirm_new_password']['ER001']; ?>'
                                    },
                                    regexp: {
                                        regexp: /^\S*$/,
                                        message: '<?= $lang_users_vars['Error']['confirm_new_password']['ER002']; ?>'
                                    },
                                    stringLength: {
                                        min: 5,
                                        max: 30,
                                        message: '<?= $lang_users_vars['Error']['confirm_new_password']['ER003']; ?>'
                                    },
                                    identical: {
                                        field: 'new_password',
                                        message: '<?= $lang_users_vars['Error']['confirm_new_password']['ER004']; ?>'
                                    }
                                }
                            },
                        }
                    });
        });
    </script>
<?php } ?>
