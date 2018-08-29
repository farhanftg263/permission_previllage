<script type="text/javascript">
$(document).ready(function() {
    $('#basicBootstrapForm').on('init.field.fv', function(e, data) {
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
                title: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_species_vars['Error']['title']['ER001'];?>'
                        },
                        stringLength: {
                            min: 2,
                            max: 100,
                            message: '<?= $lang_species_vars['Error']['title']['ER003'];?>'
                        },
//                        regexp: {
//                            regexp: /^[A-Za-z_0-9\.\-\@']+( [A-Za-z_0-9\.\-\@']+)*$/,
//                            message: '<?= $lang_species_vars['Error']['title']['ER005'];?>'
//                        },
                        remote: {
                            url: '<?= $this->Url->build(['controller' => 'Species', 'action' => 'add'], true);?>',
                            data: function(validator, $field, value) {
                                return {
                                    action:'validate',
                                    field:'title',
                                    id: <?= (!empty($id))?$id:0;?>,
                                    slug: validator.getFieldElements('title').val()
                                };
                            },
                                message: '<?= $lang_species_vars['Error']['title']['ER004'];?>',
                                type: 'POST',
                                //delay: 2000
                        },         
                    }
                },
                
                image: {
                validators: {
                    <?php if(empty($id)){?>
                    notEmpty: {
                        message: '<?= $lang_species_vars['Error']['image']['ER001'];?>'
                    },
                    <?php }?>
                    file: {
                        extension: 'gif,jpeg,png,jpg',
                        type: 'image/gif,image/jpeg,image/png',
                        message: '<?= $lang_species_vars['Error']['image']['ER002'];?>'
                        }
                    }
                },
            }
    });
 
});
</script>