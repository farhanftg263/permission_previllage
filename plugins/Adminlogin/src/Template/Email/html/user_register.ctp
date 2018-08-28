<?php 
if($content){
    echo $content;
} else {?>
<p><span style="color: #333333; font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;">Hi <?= $nickname;?>,</span></p>
<p><span style="color: #333333; font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;">Welcome to Migrate Outfitters.</span></p>
<p><span style="color: #333333; font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;">Username : <strong><span style="text-decoration: underline;"><?= $username;?></span></strong></span></p>
<p><span style="color: #333333; font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;">Password : <strong><span style="text-decoration: underline;"><?= $password;?></span></strong></span></p>
<p><span style="color: #333333; font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;"><strong><span style="text-decoration: underline;"><a href="<?= $site_url;?>" target="_blank">Click here to login</a></span></strong></span></p>
<p><span style="color: #333333; font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;">Thanks,</span></p>
<p>&nbsp;</p>
<?php }
?>

