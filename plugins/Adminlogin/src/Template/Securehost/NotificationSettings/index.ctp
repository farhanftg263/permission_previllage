<?php $this->assign('title', $title); ?>
<?php $this->assign('heading', $heading); ?>
<?php
$this->Breadcrumbs->addCrumb($heading);
?>
<?php
$this->Form->templates([
    'inputContainer' => '{{content}}',    
    'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',
    'checkboxWrapper' => '<div class="col-md-2">{{label}}</div>',
]);
?>
<div class="box-header">
    
</div>
<div class="box-body">
    <table id="example1" class="table table-bordered    ">
        <?php 
        echo $this->Form->create(
                $notification, [
            'url' => ['controller' => 'NotificationSettings', 'action' => 'index'],            
            'name' => 'basicBootstrapForm',
            'id' => 'basicBootstrapForm'
                ]
        );        
        ?>
        <tbody>
            <tr>
                <td width="10%"><label><?= __('On New Registration'); ?>:</label></td>
                <td width="30%">
                    <?php                                        
                    echo $this->Form->select('new_registration', $options, ['multiple' => 'checkbox', 'value' =>$new_registration], array('legend' => false,));
                    ?>
                </td>
            </tr>
            <tr>
                <td><label><?= __('On New Booking'); ?>:</label></td>
                <td>
                    <?php                                      
                    echo $this->Form->select('new_booking', $options, ['multiple' => 'checkbox', 'value' =>$new_booking], array('legend' => false,));
                    ?>
                </td>
            </tr>
            <tr>
                <td><label><?= __('On Deposit '); ?>:</label></td>
                <td><?php                                       
                    echo $this->Form->select('deposit', $options, ['multiple' => 'checkbox', 'value' =>$deposit], array('legend' => false,));
                    ?>
                </td>
            </tr>
            <tr>
                <td><label><?= __('On Refund Triggered '); ?>:</label></td>
                <td><?php                                       
                    echo $this->Form->select('refund_triggered', $options, ['multiple' => 'checkbox', 'value' =>$refund_triggered], array('legend' => false,));
                    ?>
                </td>
            </tr>
             <tr>
                <td><label><?= __('On Booking Rescheduled'); ?>:</label></td>
                <td><?php                                      
                    echo $this->Form->select('booking_rescheduled', $options, ['multiple' => 'checkbox', 'value' =>$booking_rescheduled], array('legend' => false,));
                    ?>
                </td>
            </tr>
            <tr>
                <td><label><?= __(' On Booking Cancelled'); ?>:</label></td>
                <td><?php                                       
                    echo $this->Form->select('booking_cancelled', $options, ['multiple' => 'checkbox', 'value' =>$booking_cancelled], array('legend' => false,));
                    ?>
                </td>
            </tr>
               <tr>
                   <td><label><?= __('On First New Message Received'); ?>:</label></td>
                   <td><?php                                      
                    echo $this->Form->select('first_new_message', $options, ['multiple' => 'checkbox', 'value' =>$first_new_message], array('legend' => false,));
                    ?>
                </td>
            </tr>            
            <tr>
                <td><label><?= __('On every new message received '); ?>:</label></td>
                <td><?php                                       
                    echo $this->Form->select('every_new_message', $options, ['multiple' => 'checkbox', 'value' =>$every_new_message], array('legend' => false,));
                    ?>
                </td>
            </tr>            
            <tr>
                <td><label><?= __('On penalty charged from the host'); ?>:</label></td>
                <td><?php                                       
                    echo $this->Form->select('penalty_charged', $options, ['multiple' => 'checkbox', 'value' =>$penalty_charged], array('legend' => false,));
                    ?>
                </td>
            </tr>
            <!--tr>
                <td><label><?= __('On New Package Added By The Favorited Host'); ?>:</label></td>
                <td><?php                                       
                    //echo $this->Form->select('new_package_added_host', $options, ['multiple' => 'checkbox', 'value' =>$new_package_added_host], array('legend' => false,));
                    ?>
                </td>
            </tr-->
            <tr>
                <td><label><?= __('On Package Being Deactivated By The Host'); ?>:</label></td>
                <td><?php                                      
                    echo $this->Form->select('package_deactivated_host', $options, ['multiple' => 'checkbox', 'value' =>$package_deactivated_host], array('legend' => false,));
                    ?>
                </td>
            </tr>
            <tr>
                <td><label><?= __('On Trip Marked As Completed'); ?>:</label></td>
                <td><?php                                      
                    echo $this->Form->select('trip_completed', $options, ['multiple' => 'checkbox', 'value' =>$trip_completed], array('legend' => false,));
                    ?>
                </td>
            </tr>
            <tr>
                <td><label><?= __('On Guest Making The Due Payment'); ?>:</label></td>
                <td><?php                                       
                    echo $this->Form->select('guest_due_payment', $options, ['multiple' => 'checkbox', 'value' =>$guest_due_payment], array('legend' => false,));
                    ?>
                </td>
            </tr>
            <tr>
                <td><label><?= __('On New Review Received By The Host'); ?>:</label></td>
                <td><?php                                      
                    echo $this->Form->select('new_review_host', $options, ['multiple' => 'checkbox', 'value' =>$new_review_host], array('legend' => false,));
                    ?>
                </td>
            </tr>            
            <tr>
                <td colspan="2">
                    <?php
                    echo $this->Form->button(
                        'Submit',
                        [
                            'type' => 'submit',
                            'class' =>  'btn btn-primary',
                        ]
                    );
                    ?>
                </td>
            </tr>
        </tbody>
        <?php echo $this->Form->end(); ?>
    </table>
</div>