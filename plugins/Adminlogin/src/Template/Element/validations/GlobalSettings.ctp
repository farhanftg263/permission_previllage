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
                reference: {
                    threshold: 30,
                    verbose: false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_global_setting_vars['Error']['reference']['ER001']; ?>'
                        },
                        stringLength: {
                            min: 2,
                            max: 100,
                            message: '<?= $lang_global_setting_vars['Error']['reference']['ER003']; ?>'
                        },
                        regexp: {
                            regexp: /^[A-Za-z_0-9\.\-\@']+( [A-Za-z_0-9\.\-\@']+)*$/,
                            message: '<?= $lang_global_setting_vars['Error']['reference']['ER005']; ?>'
                        },
                        remote: {
                            url: '<?= $this->Url->build(['controller' => 'GlobalSettings', 'action' => 'add'], true); ?>',
                            data: function (validator, $field, value) {
                                return {
                                    action: 'validate',
                                    field: 'reference',
                                    id: <?= (!empty($id)) ? $id : 0; ?>,
                                    reference: validator.getFieldElements('reference').val()
                                };
                            },
                            message: '<?= $lang_global_setting_vars['Error']['reference']['ER004']; ?>',
                            type: 'POST',
                            //delay: 2000
                        },
                    }
                },
                datatype: {
                    threshold: 30,
                    verbose: false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_global_setting_vars['Error']['datatype']['ER001']; ?>'
                        },
                        stringLength: {
                            min: 2,
                            max: 50,
                            message: '<?= $lang_global_setting_vars['Error']['datatype']['ER003']; ?>'
                        },
                        regexp: {
                            regexp: /^[A-Za-z_0-9\.\-\@\!\$']+( [A-Za-z_0-9\.\-\@\!\$']+)*$/,
                            message: '<?= $lang_global_setting_vars['Error']['datatype']['ER005']; ?>'
                        },

                    }
                },

                value: {
                    threshold: 30,
                    verbose: false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_global_setting_vars['Error']['value']['ER001']; ?>'
                        },
                        stringLength: {
                            min: 1,
                            message: '<?= $lang_global_setting_vars['Error']['value']['ER002']; ?>'
                        },
                        regexp: {
                            regexp: /^[A-Za-z_0-9\.\-\@\!\$\%']+( [A-Za-z_0-9\.\-\@\!\$\%']+)*$/,
                            message: '<?= $lang_global_setting_vars['Error']['value']['ER005']; ?>'
                        },

                    }
                },                
                
            }

        });


    });
</script>
