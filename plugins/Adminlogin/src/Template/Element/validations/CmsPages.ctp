<script type="text/javascript">
$(document).ready(function() {
    tinymce.init({
        force_br_newlines : true,
        force_p_newlines : false,
        forced_root_block : '',
        selector: 'textarea#page_content',  // change this value according to your HTML
        height: 270,
//            auto_focus: 'editor',
        plugins : 'fullscreen autolink link image lists charmap print preview code',
        images_upload_url: '<?= $this->Url->build(['controller' => 'CmsPages', 'action' => 'pageImage'], true);?>',
        images_upload_base_path: 'uploads',
        images_upload_credentials: false,
        file_picker_types: 'file image media',                        
        theme_advanced_buttons1 : "separator",
        theme_advanced_buttons2 : "bullist,numlist,separator,outdent,indent,separator,undo,redo,separator",
        theme_advanced_buttons3 : "hr,removeformat,visualaid,separator,sub,sup,separator,charmap",
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '<?= $this->Url->build(['controller' => 'CmsPages', 'action' => 'pageImage'], true);?>');
            xhr.onload = function() {
                var json;
                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }
                json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                success(json.location);
            };
            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        },
        setup: function(editor) {
            editor.on('keyup', function(e) {
                // Revalidate the hobbies field
                $('#basicBootstrapForm').formValidation('revalidateField', 'page_content');
            });
        }
    });
    // Prevent bootstrap dialog from blocking focusin
    $(document).on('focusin', function(e) {
        if ($(e.target).closest(".mce-window").length) {
            e.stopImmediatePropagation();
        }
    });   
    
    $('#basicBootstrapForm').on('init.field.fv', function(e, data) {
            var $parent = data.element.parents('.form-group'),
                $icon   = $parent.find('.form-control-feedback[data-fv-icon-for="' + data.field + '"]');
            $icon.on('click.clearing', function() {
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
                page_name: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_cms_pages_vars['Error']['page_name']['ER001'];?>'
                        },
                        stringLength: {
                            min: 2,
                            max: 100,
                            message: '<?= $lang_cms_pages_vars['Error']['page_name']['ER003'];?>'
                        },
                        regexp: {
                            regexp: /^[A-Za-z_0-9\.\-']+( [A-Za-z_0-9\.\-']+)*$/,
                            message: '<?= $lang_cms_pages_vars['Error']['page_name']['ER005'];?>'
                        },     
                    }
                },
                page_title: {
                threshold: 30,
                verbose:false,
                validators: {
                    notEmpty: {
                        message: '<?= $lang_cms_pages_vars['Error']['title']['ER001'];?>'
                    },
                    stringLength: {
                        min: 2,
                        max: 255,
                        message: '<?= $lang_cms_pages_vars['Error']['title']['ER003'];?>'
                    },
                    regexp: {
                            regexp: /^[A-Za-z_0-9\.\-']+( [A-Za-z_0-9\.\-']+)*$/,
                            message: '<?= $lang_cms_pages_vars['Error']['title']['ER005'];?>'
                        },                               
                    
                    }
                },
                meta_keyword: {
                    threshold: 100,
                    verbose:false,
                    validators: {
                        stringLength: {
                            min: 2,
                            max: 500,
                            message: '<?= $lang_cms_pages_vars['Error']['meta_keyword']['ER005'];?>'
                        },
                    }
                }, 
                meta_description: {
                    threshold: 100,
                    verbose:false,
                    validators: {
                        stringLength: {
                            min: 2,
                            max: 500,
                            message: '<?= $lang_cms_pages_vars['Error']['meta_description']['ER005'];?>'
                        },
                    }
                },
                page_slug: {
                    threshold: 30,
                    verbose:false,
                    validators: {
                        notEmpty: {
                            message: '<?= $lang_cms_pages_vars['Error']['page_slug']['ER001'];?>'
                        },
                        stringLength: {
                            min: 2,
                            max: 50,
                            message: '<?= $lang_cms_pages_vars['Error']['page_slug']['ER005'];?>'
                        },
                    }
                },
                page_content: {
                    threshold: 100,
                    verbose:false,
                    validators: {
                        callback: {
                            message: '<?= $lang_cms_pages_vars['Error']['page_content']['ER002'];?>',
                            callback: function(value, validator, $field) {
                                // Get the plain text without HTML
                                var text = tinyMCE.activeEditor.getContent({
                                    format: 'text'
                                });
                                return text.length > 5;
                            }
                        }
                    }
                },
            }
    });
 
 
});
</script>
