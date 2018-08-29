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
                        }
                    }
                },
                description: {
                    threshold: 2,
                    verbose:false,
                    validators: {
                        stringLength: {
                            min: 2,
                            max: 100,
                            message: '<?= $lang_roles_vars['RoleError']['description']['ER001'];?>'
                        },
                    }
                },
            }

        });


    });
</script>
