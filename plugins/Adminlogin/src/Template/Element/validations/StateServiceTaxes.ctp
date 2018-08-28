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
                            message: '<?= $lang_state_service_tax_vars['Error']['state_id']['ER001']; ?>'
                        },
                        remote: {
                            url: '<?= $this->Url->build(['controller' => 'StateServiceTaxes', 'action' => 'add'], true); ?>',
                            data: function (validator, $field, value) {
                                return {
                                    action: 'validate',
                                    field: 'state_id',
                                    id: <?= (!empty($id)) ? $id : 0; ?>,
                                    state_id: validator.getFieldElements('state_id').val()
                                };
                            },
                            message: '<?= $lang_state_service_tax_vars['Error']['state_id']['ER004']; ?>',
                            type: 'POST',
                            //delay: 2000
                        },
                    }
                },               
                service_tax: {
                    threshold: 30,
                    verbose: false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_state_service_tax_vars['Error']['service_tax']['ER001']; ?>'
                        },
                        stringLength: {
                            min: 1,
                            message: '<?= $lang_state_service_tax_vars['Error']['service_tax']['ER002']; ?>'
                        },
                        between: {
                            min: 0,
                            max: 100,
                            message: '<?= $lang_state_service_tax_vars['Error']['service_tax']['ER003']; ?>'
                        }
                    }
                },
                
            }

        });


    });
</script>
