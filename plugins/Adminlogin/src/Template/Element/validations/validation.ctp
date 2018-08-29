<?php
/*
 * Element- validation: This validation element dynamically call 
 * separate validation ctp for every controller
 * Author: Pradeep Chaurasia
 * Created Date: 06/Feb/2018
 * Modified By: Pradeep Chaurasia
 * Modified Date: 06/Feb/2018
 */
?>
<?php
if(($this->request->controller == 'CmsPages' && in_array($this->request->action, ['add','edit'])) || ($this->request->controller == 'EmailTemplates' && in_array($this->request->action, ['add','edit'])) ){
    echo $this->Html->script('Adminlogin.tinymce/js/tinymce/tinymce.min',['type'=>"text/javascript"]);
}
?>
<!-- validations -->
<?= $this->Html->script('Adminlogin.formValidation.min',['type'=>"text/javascript"]);?>
<?= $this->Html->script('Adminlogin.formvalidation.bootstrap.min',['type'=>"text/javascript"]);?>
<?= $this->element('validations/'.$this->request->controller); ?>
