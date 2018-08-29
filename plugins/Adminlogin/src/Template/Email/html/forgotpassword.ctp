<div class='movableContent'>
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
        <tr height="30">
            <td width="200">&nbsp;</td>
            <td width="200">&nbsp;</td>
            <td width="200">&nbsp;</td>
        </tr>
        <tr>
            <td width="200" valign="top">&nbsp;</td>
            <td width="200" valign="top" align="center">
                <div class="contentEditableContainer contentImageEditable">
                    <div class="contentEditable" align='center' >
                        <?php echo $this->Html->image($baseUrl.'/adminlogin/img/footer-logo.png', array('data-default' => 'placeholder', 'alt' => 'Logo'));?>
                         <?php //echo $this->Html->image('Adminlogin.footer-logo.png', ['alt' => 'Logo']);?>
                    </div>
                </div>
            </td>
            <td width="200" valign="top">&nbsp;</td>
        </tr>
        <tr height="10">
            <td width="200">&nbsp;</td>
            <td width="200">&nbsp;</td>
            <td width="200">&nbsp;</td>
        </tr>
    </table>
</div>
<div class='movableContent'>
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
        <tr>
            <td width="100%" colspan="3" align="center" style="padding-bottom:10px;">
                <div class="contentEditableContainer contentTextEditable">
                    <div class="contentEditable" align='center' >
                        <h2 ><?= __($subject);?></h2>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="100">&nbsp;</td>
            <td width="600" align="center">
                <div class="contentEditableContainer contentTextEditable">
                    <div class="contentEditable" align='left' >
                        <p ><?= __('Hi')?> <strong><?= __($receiver_name);?></strong>,
                            <br/>
                            <br/>
                            <?= __('Your request to reset your password has been approved for 24 hours.')?>                            
                            <br/>
                            <br/>
                            <a href="<?= $reset_password_link;?>"><?= __('Click Here')?></a> <?= __('to reset your password or copy below link to your browser')?>-
                            <br/>
                            <br/>
                            <?= $reset_password_link;?>
                            <br/>
                            <br/>
                            <?= __('Thanks')?>
                            <br/>
                            <?= __('Migrate Outfitters Support')?>
                        </p>
                    </div>
                </div>
            </td>
            <td width="100">&nbsp;</td>
        </tr>
    </table>
</div>