<?php
namespace Adminlogin\Mailer;

use Cake\Mailer\Mailer;
use Cake\Core\Configure;
/**
 * AdminUser mailer.
 */
class AdminUserMailer extends Mailer
{
    
    /**
     * Mailer's name.
     *
     * @var string
     */
    static public $name = 'AdminUser';
    public $notification_setting = array();
    public function __construct(\Cake\Mailer\Email $email = null) {
        parent::__construct($email);
        $setting = \Cake\ORM\TableRegistry::get('NotificationSettings')
                ->find('all')->first();
        $this->notification_setting = $setting;
    }
    
    public function forgotPassword($options){
        $flag = true;
        $to = $cc = $bcc = '';
        $attachments = $viewVars = array(); 
        $template = 'Adminlogin.forgotpassword';
        $layout= 'Adminlogin.admin_user';
        $subject = 'Reset Your Password'; 
        $from = 'pradeep.chaurasia.newmediaguru@gmail.com';
        //pr($options);//die;
        //pr(Configure::read('Adminlogin.App'));die;
        if(!empty(Configure::read('Adminlogin.App')['baseUrl'])){            
            $viewVars['baseUrl'] = Configure::read('Adminlogin.App')['baseUrl'];
            $viewVars['subject'] = $subject;            
        }
        
        if(!empty($options['attribs'])){
            if(!empty($options['attribs']['to'])){
                //comma (,) separated if multiple eamil send
                $to = $options['attribs']['to'];
            }else{
               $flag = false;  
            }
            if(!empty($options['attribs']['from'])){
                $from = $options['attribs']['from'];
            }
            if(!empty($options['attribs']['subject'])){
                $subject = $options['attribs']['subject'];
            }
            if(!empty($options['attribs']['cc'])){
                $cc = $options['attribs']['cc'];
            }
            if(!empty($options['attribs']['bcc'])){
                $bcc = $options['attribs']['bcc'];
            }
        }
        if(!empty($options['files'])){            
            if(is_array($options['files']) && !empty($options['files'])){
                foreach($options['files'] as $attachment){
                    if(file_exists($attachment)){
                        $attachments[] = $attachment;
                    }
                }
            }
        }
        if(!empty($options['vars'])){
            $viewVars = array_merge($viewVars, $options['vars']);
        }
        //echo $to;
        //pr($viewVars);die;
        ################ Mailer ################
        if($flag){             
            $this
                ->emailFormat('html')
                ->template($template, $layout)// By default template with same name as method name is used.
                ->subject(sprintf('%s', $subject))
                ->to($to)
                ->viewVars($viewVars);
        }        
    }
    
    public function userRegister($options){
        if(!empty($this->notification_setting)){
            $settings = explode(',',$this->notification_setting['new_registration']);
            if(in_array(1, $settings)){
                $flag = true;
                $to = $cc = $bcc = '';
                $attachments = $viewVars = array(); 
                $template = 'Adminlogin.user_register';
                $to = $options->email;
                $layout= 'Adminlogin.admin_user';
                $subject = 'You are invited to join MigrateOutfitter Account';
                $from = 'pradeep.chaurasia.newmediaguru@gmail.com';
                //pr($options);//die;
                //pr(Configure::read('Adminlogin.App'));die;

                if(!empty(Configure::read('Adminlogin.App')['baseUrl'])){            
                    $viewVars['baseUrl'] = Configure::read('Adminlogin.App')['baseUrl'];
                    $viewVars['subject'] = $subject;            
                }
                $options = json_decode(json_encode($options),true);
                $viewVars = array_merge($viewVars, $options);
                $template_name = \Cake\ORM\TableRegistry::get('EmailTemplates')
                        ->find()->select()->where(['slug' => 'Sign_up'])
                        ->first();
                if(!empty($template_name->message)){
                    $html = htmlspecialchars_decode($template_name->message);
                    $content = $this->setContent($html,$viewVars);
                    $viewVars['content'] = $content;
                }
                //echo $to;
                //pr($viewVars);die;
                ################ Mailer ################
                if($flag){             
                    $this
                        ->emailFormat('html')
                        ->template($template, $layout)// By default template with same name as method name is used.
                        ->subject(sprintf('%s', $subject))
                        ->to($to)
                        ->from(['contact@migrateoutfitter.com' => 'MigrateOutfitter'])
                        ->viewVars($viewVars);
                }
                
            }
        }
       return true;         
    }
    
    private function setContent($content,$options){
       if(!empty($options)){
           foreach($options as $key => $value){
               $content = str_replace('{{'.$key.'}}', $value, $content);
           }
       }
       return $content;
    }

    /*
     * Functionality : Rate And Review to Package
     * Author: Nishant
     */
    public function cancelled_adventure_guest($options)
    {
        $this->loadModel('Adminlogin.EmailTemplates');
        $EmailTemplates = $this->EmailTemplates->find('all')->select(['message'])->where(['slug' => 'cancelled_adventure_guest'])->first();
        $content = str_replace('{NAME}', $options['booked_by'], $EmailTemplates['message']);
        $content = str_replace('{EMAIL_US}', SITE_URL, $content);
        $content = str_replace('{CALL_US}', SITE_URL, $content);
        $content = str_replace('{CANCELLATION_POLICY}', SITE_URL.'guest/package-details/'.$options['package_id'].'/'.$options['booking_id'].'/', $content);
        $options['content'] = html_entity_decode($content);
               
        $flag = true;
        $to = $cc = $bcc = '';
        $attachments = $viewVars = array(); 
        $template = 'Adminlogin.booking';
        $layout = 'Adminlogin.migrate_blank';
        $subject = 'Cancelled Adventure'; 
        //$from = 'farhan.hashmi.newmediaguru@gmail.com';
        $options['is_from'] = ADMIN_GUEST_CANCELLED_ADEVENTURE;     
        if(!empty($options))
        {
            $viewVars = array_merge($viewVars, $options);
        }
       
        $to = $options['guest_email'];
                
        ################ Mailer ################
        if($flag){             
            $this
                ->emailFormat('html')
                ->template($template, $layout)// By default template with same name as method name is used.
                ->subject(sprintf('%s', $subject))
                ->from('contact@migrateoutfitters.com','Migrate Outfitters')
                ->to($to)
                ->viewVars($viewVars);
        }   
    }

    /*
     * Functionality : Rate And Review to Package
     * Author: Nishant
     */
    public function cancelled_adventure_host($options)
    {
        $this->loadModel('Adminlogin.EmailTemplates');
        $EmailTemplates = $this->EmailTemplates->find('all')->select(['message'])->where(['slug' => 'cancelled_adventure_host'])->first();
        $content = str_replace('{NAME}', $options['hosted_by'], $EmailTemplates['message']);
        $content = str_replace('{EMAIL_US}', SITE_URL, $content);
        $content = str_replace('{CALL_US}', SITE_URL, $content);
        $content = str_replace('{CANCELLATION_POLICY}', SITE_URL.'guest/package-details/'.$options['package_id'].'/'.$options['booking_id'].'/', $content);
        $options['content'] = html_entity_decode($content);
               
        $flag = true;
        $to = $cc = $bcc = '';
        $attachments = $viewVars = array(); 
        $template = 'Adminlogin.booking';
        $layout = 'Adminlogin.migrate_blank';
        $subject = 'Cancelled Adventure'; 
        //$from = 'farhan.hashmi.newmediaguru@gmail.com';
        $options['is_from'] = ADMIN_HOST_CANCELLED_ADEVENTURE;     
        if(!empty($options))
        {
            $viewVars = array_merge($viewVars, $options);
        }
       
        $to = $options['host_email'];
                
        ################ Mailer ################
        if($flag){             
            $this
                ->emailFormat('html')
                ->template($template, $layout)// By default template with same name as method name is used.
                ->subject(sprintf('%s', $subject))
                ->from('contact@migrateoutfitters.com','Migrate Outfitters')
                ->to($to)
                ->viewVars($viewVars);
        }   
    }


    /*
     * Functionality : Rate And Review to Package
     * Author: Nishant
     */
    public function refund_trigger($options)
    {
        $this->loadModel('Adminlogin.EmailTemplates');
        $EmailTemplates = $this->EmailTemplates->find('all')->select(['message'])->where(['slug' => 'refund_trigger'])->first();
        $content = str_replace('{NAME}', $options['booked_by'], $EmailTemplates['message']);
        $content = str_replace('{EMAIL_US}', SITE_URL, $content);
        $content = str_replace('{CALL_US}', SITE_URL, $content);
        $content = str_replace('{CANCELLATION_POLICY}', SITE_URL.'guest/package-details/'.$options['package_id'].'/'.$options['booking_id'].'/', $content);
        $options['content'] = html_entity_decode($content);
               
        $flag = true;
        $to = $cc = $bcc = '';
        $attachments = $viewVars = array(); 
        $template = 'Adminlogin.booking';
        $layout = 'Adminlogin.migrate_blank';
        $subject = 'Refund'; 
        //$from = 'farhan.hashmi.newmediaguru@gmail.com';
        $options['is_from'] = ADMIN_GUEST_REFUND_AMOUNT;     
        if(!empty($options))
        {
            $viewVars = array_merge($viewVars, $options);
        }
       
        $to = $options['guest_email'];
                
        ################ Mailer ################
        if($flag){             
            $this
                ->emailFormat('html')
                ->template($template, $layout)// By default template with same name as method name is used.
                ->subject(sprintf('%s', $subject))
                ->from('contact@migrateoutfitters.com','Migrate Outfitters')
                ->to($to)
                ->viewVars($viewVars);
        }   
    }

    /*
     * Functionality : Rate And Review to Package
     * Author: Nishant
     */
    public function host_commitment_rate($options)
    {
        $this->loadModel('Adminlogin.EmailTemplates');
        $EmailTemplates = $this->EmailTemplates->find('all')->select(['message'])->where(['slug' => 'host_commitment_rate'])->first();
        $content = str_replace('{NAME}', $options['hosted_by'], $EmailTemplates['message']);
        $content = str_replace('{EMAIL_US}', SITE_URL, $content);
        $content = str_replace('{CALL_US}', SITE_URL, $content);
        $options['content'] = html_entity_decode($content);
               
        $flag = true;
        $to = $cc = $bcc = '';
        $attachments = $viewVars = array(); 
        $template = 'Adminlogin.booking';
        $layout = 'Adminlogin.migrate_blank';
        $subject = 'Commitment Rate'; 
        //$from = 'farhan.hashmi.newmediaguru@gmail.com';
        $options['is_from'] = ADMIN_HOST_COMMITMENT_RATE;     
        if(!empty($options))
        {
            $viewVars = array_merge($viewVars, $options);
        }
       
        $to = $options['host_email'];
                
        ################ Mailer ################
        if($flag){             
            $this
                ->emailFormat('html')
                ->template($template, $layout)// By default template with same name as method name is used.
                ->subject(sprintf('%s', $subject))
                ->from('contact@migrateoutfitters.com','Migrate Outfitters')
                ->to($to)
                ->viewVars($viewVars);
        }   
    }

    /*
     * Functionality : Booking reschedule by host
     * Author: Nishant
     */
    public function reschedule_booking_host($options)
    {
        $this->loadModel('Adminlogin.EmailTemplates');
        $EmailTemplates = $this->EmailTemplates->find('all')->select(['message'])->where(['slug' => 'reschedule_booking_host'])->first();
        $content = str_replace('images/', SITE_URL.FRONTEND_IMG_PATH, $EmailTemplates['message']);
        $content = str_replace('{BOOKING_ID}', $options['booking_id'], $content);  
        $content = str_replace('{NAME}', $options['hosted_by'], $content);
        $content = str_replace('{BOOKING_DETAIL_PATH}', SITE_URL.'guest/package-details/'.$options['package_id'].'/'.$options['booking_id'].'/', $content);
        $content = str_replace('{CALENDAR_PATH}', SITE_URL.'host/dashboard/', $content);
        $content = str_replace('{BOOKING_DETAIL_IMGPATH}', SITE_URL.FRONTEND_IMG_PATH.'view-booking-detail.png', $content);
        $content = str_replace('{CALENDAR_IMGPATH}', SITE_URL.FRONTEND_IMG_PATH.'view-my-calender.png', $content);
        
        $options['content'] = html_entity_decode($content);
               
        $flag = true;
        $to = $cc = $bcc = '';
        $attachments = $viewVars = array(); 
        $template = 'Adminlogin.booking';
        $layout= 'Adminlogin.migrate_blank';
        $subject = 'Reschedule Package'; 
        //$from = 'farhan.hashmi.newmediaguru@gmail.com';
        $options['is_from'] = BOOKING_EMAIL_RESCHEDULE_HOST;   
        if(!empty($options))
        {
            $viewVars = array_merge($viewVars, $options);
        }
       
        $to = $options['host_email'];
                
        ################ Mailer ################
        if($flag){             
            $this
                ->emailFormat('html')
                ->template($template, $layout)// By default template with same name as method name is used.
                ->subject(sprintf('%s', $subject))
                ->from('contact@migrateoutfitters.com','Migrate Outfitters')
                ->to($to)
                ->viewVars($viewVars);
        }   
    }
     /*
     * Functionality : Booking reschedule for guest
     * Author: Nishant
     */
    public function reschedule_booking_guest($options)
    {
        $this->loadModel('Adminlogin.EmailTemplates');
        $EmailTemplates = $this->EmailTemplates->find('all')->select(['message'])->where(['slug' => 'reschedule_booking_guest'])->first();
        $content = str_replace('images/', SITE_URL.FRONTEND_IMG_PATH, $EmailTemplates['message']);
        $content = str_replace('{BOOKING_ID}', $options['booking_id'], $content);
        $content = str_replace('{NAME}', $options['name'], $content);
        $content = str_replace('{PACKAGE_DETAIL_PATH}', SITE_URL.'guest/package-details/'.$options['package_id'].'/'.$options['booking_id'].'/', $content);
        $content = str_replace('{PACKAGE_DETAIL_IMG}', SITE_URL.FRONTEND_IMG_PATH.'view-package-details.png', $content);
        $content = str_replace('{TRIP_DETAIL}', $options['trip_details'], $content);
        $content = str_replace('{WHAT_TO_BRING}', $options['guest_to_know'], $content);
        $content = str_replace('{REMAINING}', number_format($options['remaining_amount'],2), $content);
        $content = str_replace('{CARD_BRAND}', $options['card_brand'], $content);
        $content = str_replace('{LAST_FOUR_DIGIT}', $options['last4'], $content);
        $options['content'] = html_entity_decode($content);
               
        $flag = true;
        $to = $cc = $bcc = '';
        $attachments = $viewVars = array(); 
        $template = 'Adminlogin.booking';
        $layout = 'Adminlogin.migrate_blank';
        $subject = 'Reschedule Package'; 
        //$from = 'farhan.hashmi.newmediaguru@gmail.com';
        $options['is_from'] = BOOKING_EMAIL_RESCHEDULE_GUEST;     
        if(!empty($options))
        {
            $viewVars = array_merge($viewVars, $options);
        }
       
        $to = $options['email'];
                
        ################ Mailer ################
        if($flag){             
            $this
                ->emailFormat('html')
                ->template($template, $layout)// By default template with same name as method name is used.
                ->subject(sprintf('%s', $subject))
                ->from('contact@migrateoutfitters.com','Migrate Outfitters')
                ->to($to)
                ->viewVars($viewVars);
        }   
    }
    /*
     * Functionality : Block unblock users
     * Author: Farhan
     */
    public function block_unblock($options)
    {
        $this->loadModel('Adminlogin.EmailTemplates');
        $status_title = ($options['status'] == 1) ? 'Block':'Unblock';
        //
        if($status_title == 'Block'){
            $subjectStatus = 'Account Suspended';
        }elseif($status_title == 'Unblock'){
            $subjectStatus = 'Unblock User';
        }
        
        if($options['status'] == 1)
        {
            $EmailTemplates = $this->EmailTemplates->find('all')->select(['message'])->where(['slug' => 'suspende_admin_user'])->first();
        }
        else
        {
            $EmailTemplates = $this->EmailTemplates->find('all')->select(['message'])->where(['slug' => 'admin_block_unblock_user'])->first();
        }
        $content = str_replace('images/', SITE_URL.FRONTEND_IMG_PATH, $EmailTemplates['message']);
        $content = str_replace('{STATUS}', $status_title, $content);
        $options['content'] = html_entity_decode($content);
        
        $flag = true;
        $to = $cc = $bcc = '';
        $attachments = $viewVars = array(); 
        $template = 'Adminlogin.booking';
        $layout = 'Adminlogin.migrate_blank';
        $subject = $subjectStatus; 
        //$from = 'farhan.hashmi.newmediaguru@gmail.com';
        $options['is_from'] = 19;     
        if(!empty($options))
        {
            $viewVars = array_merge($viewVars, $options);
        }
       
        $to = $options['email'];
                
        ################ Mailer ################
        if($flag){             
            $this
                ->emailFormat('html')
                ->template($template, $layout)// By default template with same name as method name is used.
                ->subject(sprintf('%s', $subject))
                ->from('contact@migrateoutfitters.com','Migrate Outfitters')
                ->to($to)
                ->viewVars($viewVars);
        }  
    }

    /*
     * Functionality : Sign up host/guest 
     * Author: Farhan
     */
    public function sign_up_host_guest($options)
    {
        $to = $options['email'];
        $attachments = $viewVars = array(); 
        $template = 'Adminlogin.booking';
        $layout = 'Adminlogin.migrate_blank';
        $subject = 'Welcome to Migrate Outfitters';
        
        $slug = ($options['role_id'] == GUEST) ? 'guest_after_sign_up':'host_after_sign_up';
        $this->loadModel('Adminlogin.EmailTemplates');
        $EmailTemplates = $this->EmailTemplates->find('all')->select(['message'])->where(['slug' => $slug])->first();
        
        $profiel_url = ($options['role_id'] == GUEST) ? SITE_URL.'guest/profile':SITE_URL.'host/profile';
        $learn_more = ($options['role_id'] == GUEST) ? SITE_URL.'being-a-guest':SITE_URL.'host-tips';
        
        $content = str_replace('images/', SITE_URL.FRONTEND_IMG_PATH, $EmailTemplates['message']);
        $content = str_replace('{NAME}', $options['name'], $content);
        $content = str_replace('{PROFILE}', $profiel_url, $content);
        $content = str_replace('{BROWSER_ADVENTURE}', SITE_URL, $content);
        $content = str_replace('{CREATE_PACKAGE}', SITE_URL.'packages/create-packages', $content);
        $content = str_replace('{FACEBOOK_URL}', MIGRATE_FACEBOOK_URL, $content);
        $content = str_replace('{TWITTER_URL}', MIGRATE_TWITTER_URL, $content);
        $content = str_replace('{INSTAGRAM_URL}', MIGRATE_INSTAGRAM_URL, $content);
        $content = str_replace('{BEING_GUEST}', $learn_more, $content);
        $content = str_replace('{FAQ}', SITE_URL.'faq', $content);
        
        $options['is_from'] = USER_SIGN_UP;   
        $options['content'] = html_entity_decode($content);
        if(!empty($options))
        {
            $viewVars = array_merge($viewVars, $options);
        }
        $flag = true;
        ################ Mailer ################
        if($flag){             
            $this
                ->emailFormat('html')
                ->template($template, $layout)// By default template with same name as method name is used.
                ->subject(sprintf('%s', $subject))
                ->from('contact@migrateoutfitters.com','Migrate Outfitters')
                ->to($to)
                ->viewVars($viewVars);
        } 
    }
}
