<?php
    //Booking state email template element
    switch($is_from)
    {
        case ADMIN_GUEST_CANCELLED_ADEVENTURE:
            $content = str_replace('{CANCELLED_ADEVENTURE}', $this->element('email/guest_cancelled_adventure'), $content);
            echo $content; break;
        case ADMIN_GUEST_REFUND_AMOUNT:
            $content = str_replace('{REFUND_AMOUNT}', $this->element('email/guest_refund_amount'), $content);
            echo $content; break;
        case ADMIN_HOST_COMMITMENT_RATE:
            $content = str_replace('{COMMITMENT_RATE}', $this->element('email/host_commitment_rate'), $content);
            echo $content; break;
        case ADMIN_HOST_CANCELLED_ADEVENTURE:
            $content = str_replace('{CANCELLED_ADEVENTURE}', $this->element('email/host_cancelled_adventure'), $content);
            echo $content; break;
        case BOOKING_EMAIL_RESCHEDULE_HOST:
            $content = str_replace('{PACKAGE_DETAIL}', $this->element('email/booking_host_reschedule_package_detail'), $content);
            $content = str_replace('{GUEST_LIST}', $this->element('email/booking_host_reschedule_guest_list'), $content);
            $content = str_replace('{PAYMENT}', $this->element('email/booking_host_reschedule_payment'), $content);
            echo $content; break;
        case BOOKING_EMAIL_RESCHEDULE_GUEST:
            $content = str_replace('{PACKAGE_DETAIL}', $this->element('email/booking_guest_reschedule_package_detail'), $content);
            $content = str_replace('{GUEST_LIST}', $this->element('email/booking_guest_reschedule_guest_list'), $content);
            $content = str_replace('{PAYMENT}', $this->element('email/booking_guest_reschedule_payment'), $content);
            echo $content; break;
        case 19: 
            echo $content; break;
        case USER_SIGN_UP : 
            echo $content;break;
        default : ?><div>Template not found</div><?php
    }
    
?>