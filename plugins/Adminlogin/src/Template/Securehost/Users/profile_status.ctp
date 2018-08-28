<table id="example1" class="table table-bordered table-striped">
    <tbody>

        <?php
        
        if (!empty($userDetails->user_roles)) {
            echo $this->Form->create(
                    'profile_status', [
                'url' => ['controller' => 'Users', 'action' => 'profileStatus'],
                'type' => 'post',
                'name' => 'basicBootstrapForm',
                'id' => 'basicBootstrapForm'
                    ]
            );
            echo $this->Form->hidden('id',['id' => 'user_id','value' => $userDetails->id]); 
            foreach ($userDetails->user_roles as $role) {
                ?>
                <tr>
                    <td width="18%" ><?= $role->role->name; ?></td>
                    <?php
                    $status = $userDetails->is_email_verified == 1 ? 'active' : 'inactive';
                    $options = array('active' => 'Active', 'inactive' => 'Inactive');
                    echo $this->Form->input($role->role->name, [
                        'templates' => [
                            'radioWrapper' => '<td width="2%" style="padding:20px;">{{label}}</td>'
                        ],
                        'type' => 'radio',
                        'options' => $options,
                        'value' => $status,
                        'label' => false
                    ]);
                }
                echo '</tr>';
                echo '<tr><td>';
                echo $this->Form->button(
                        'Submit', [
                    'type' => 'button',
                            'id' => 'submit_click',
                    'class' => 'btn btn-primary',
                        ]
                );
                echo '</td></tr>';
                echo $this->Form->end();
            }
            ?>


    </tbody>
</table>
<?= $this->Html->script('Adminlogin.jquery-2.2.3.min'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#submit_click").on('click',function(){
            var user_id = $('#user_id').val();
            $(this).after("<img id='loader_img' src='data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==' />");
            //send ajax post request.
            $.post("<?php echo $this->Url->build(['action'=>'profileStatus',$userDetails->id]);?>",$("#basicBootstrapForm").serialize(),
             function(data, status){
                $("#loader_img").remove(); 
                if(data == 1)
                {
                    parent.window.location = "<?php echo $this->Url->build(['action'=>'index']);?>";
                }
                else
                {
                    alert('Something went wrong!');
                }
             });
        });
    });
    </script>