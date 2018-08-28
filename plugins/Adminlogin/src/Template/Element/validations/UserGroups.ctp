<script type="text/javascript">
$(document).ready(function() {
    $('#frmUserGroups').formValidation({
        framework: 'bootstrap',
        fields: {                
            name: {
                threshold: 30,
                verbose:false,
                validators: {
                    notEmpty: {
                        message: '<?= $lang_user_groups_vars['Error']['name']['ER001'];?>'
                    },
                    stringLength: {
                        min: 2,
                        message: '<?= $lang_user_groups_vars['Error']['name']['ER002'];?>'
                    }, 
                    remote: {
                        url: '<?= $this->Url->build(['controller' => 'UserGroups', 'action' => 'add'], true);?>',
                        data: function(validator, $field, value) {
                            return {
                                action:'validate',
                                field:'name',
                                id: <?= (!empty($id))?$id:0;?>,
                                name: validator.getFieldElements('name').val()
                            };
                        },
                        message: '<?= $lang_user_groups_vars['Error']['name']['ER004'];?>',
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
                        message: '<?= $lang_user_groups_vars['Error']['description']['ER001'];?>'
                    },
                }
            },
        }
    });
});
</script>