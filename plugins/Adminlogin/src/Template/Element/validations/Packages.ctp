<script type="text/javascript">
$(document).ready(function() { 

    $('#packageForm')
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
                package_name: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= PACKAGE_NAME_ERROR; ?>'
                        },
                    }
                },
                per_hunter: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= PACKAGE_PRICE_ERROR1; ?>'
                        },
                    }
                },
                price: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= PACKAGE_PRICE_ERROR1; ?>'
                        },
                    }
                },
            }
    });

    $("#per_hunter,#price").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
});
</script>
