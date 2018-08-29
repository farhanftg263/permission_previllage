<?php $this->assign('title', $title);?>
<?php $this->assign('heading', $heading);?>
<div id="response" style="color:green"></div><br>
<div id="html" class="demo">
    <ul>
        <?php 
        foreach ($tree_view as $tree): ?>
        <li id="<?= $tree['id']; ?>" data-jstree='{ "opened" : true }'><?= $tree['name']; ?>
            <?php if(isset($tree['children'])): ?>
            <ul>
                <?php foreach ($tree['children'] as $child): ?>
                <li id="<?= $child['id'];?>"  data-jstree='{ "selected" : <?= in_array($child['id'], $permission)?true:false;?> }'><?= $child['name']; ?>
                    
                
                </li>
                    
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>
</div><br>

<?php
    echo $this->Form->create(
        'Roles', 
        [
            'url' => ['controller' => 'Roles', 'action' => 'addpermission'],
            'type' => 'file',
            'name'  => 'roleForm',
            'id'  => 'basicBootstrapForm'
        ]
        );
?>
<?php echo $this->Form->hidden('module_ids',['id' => "module_ids"]); ?>
<?php echo $this->Form->hidden('role_id',['value' => $role_id]); ?>
<button id="ajax-submit" type="submit" class="btn btn-primary">Submit</button>
<?php echo $this->Form->end(); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function()
    {
        $('#html').jstree({
        plugins: ["checkbox", "types"],
        core: {
                    themes: {
                        responsive: !1,
                        "icons" : false
                    },
                },
                types: {
                    "default": {
                        icon: "fa fa-industry"
                    },
                }
        });
    });
    
    $("#ajax-submit").click(function()
    {
        var selected_ids = $("#html").jstree("get_selected");
       
        var cc_ids = [];
        for(var i in selected_ids)
        {
            if ($.isNumeric(selected_ids[i]))
            {
                cc_ids.push(selected_ids[i]);
            }
        }
        console.log(cc_ids);
        $("#module_ids").val(cc_ids);
        
        //ajax
        // show that something is loading
        $('#response').html("<b>Loading response...</b>");

        // Call ajax for pass data to other place
        $.ajax({
            type: 'POST',
            url: '<?= $this->Url->build(['controller' => 'Roles', 'action' => 'addpermission'], true);?>',
            data: $("#basicBootstrapForm").serialize() // getting filed value in serialize form
        })
        .done(function(data)
        { 
            // show the response
            window.top.location.href = "<?= $this->Url->build(['controller' => 'Roles', 'action' => 'index'], true);?>"; 
  
        })
        .fail(function()
        {
            // just in case posting your form failed
            alert("Something went wrong." );
        });
        // to prevent refreshing the whole page page
        return false;
    });

</script>
                                               
                      