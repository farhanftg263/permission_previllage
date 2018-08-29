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
                state_id: {
                    threshold: 30,
                    verbose: false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_state_licence_vars['Error']['state']['ER001']; ?>'
                        },
                        remote: {
                            url: '<?= $this->Url->build(['controller' => 'StateLicences', 'action' => 'add'], true); ?>',
                            data: function (validator, $field, value) {
                                return {
                                    action: 'validate',
                                    field: 'state_id',
                                    id: <?= (!empty($id)) ? $id : 0; ?>,
                                    state_id: validator.getFieldElements('state_id').val()
                                };
                            },
                            message: '<?= $lang_state_licence_vars['Error']['state']['ER004']; ?>',
                            type: 'POST',
                            //delay: 2000
                        },
                    }
                },
                name: {
                    threshold: 30,
                    verbose: false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_state_licence_vars['Error']['name']['ER001']; ?>'
                        },
                        stringLength: {
                            min: 2,
                            max: 50,
                            message: '<?= $lang_state_licence_vars['Error']['name']['ER003']; ?>'
                        },
                        regexp: {
                            regexp: /^[A-Za-z_0-9\.\-\@\!\$']+( [A-Za-z_0-9\.\-\@\!\$']+)*$/,
                            message: '<?= $lang_state_licence_vars['Error']['name']['ER005']; ?>'
                        },

                    }
                },

                link: {
                    threshold: 30,
                    verbose: false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_state_licence_vars['Error']['link']['ER001']; ?>'
                        },
                        stringLength: {
                            min: 10,
                            message: '<?= $lang_state_licence_vars['Error']['link']['ER002']; ?>'
                        },
                        uri: {
                            allowLocal: true,
                            message: '<?= $lang_state_licence_vars['Error']['link']['ER005']; ?>'
                        }

                    }
                },
                
                
            }

        });


    });
</script>
