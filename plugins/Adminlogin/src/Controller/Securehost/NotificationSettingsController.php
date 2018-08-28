<?php
namespace Adminlogin\Controller\Securehost;

use Adminlogin\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;

class NotificationSettingsController extends AppController {

    protected $auth_role; //1=>Admin, 2=>Parent
    protected $auth_id;
    protected $auth_email;
    protected $auth_username;
    protected $auth_user_fullname;
    protected $auth_user_created;
    protected $lang_notification_vars;

    /**
     * Function Name:  initialize
     * Type: Public
     * Utility: To initialize common settings for whole controller actions
     * @param : rows, label, placeholder  
     * Output: on success redirect to admin notification page.
     * Author: Pradeep Chaurasia
     * Created Date: 15/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 15/Feb/2018
     */
    public function initialize() {
        try {
            parent::initialize();
            $this->loadComponent('Adminlogin.Common');
            $this->loadModel('Adminlogin.AdminUsers');
            $this->Common->loadLangFile(['Notification']);
            $this->lang_notification_vars = Configure::read('Notification');
            $this->set('lang_notification_vars', $this->lang_notification_vars);
            $this->viewBuilder()->helpers(['Adminlogin.Breadcrumbs']);
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
    }

    /**
     * Function Name: beforeFilter
     * Type: Public
     * Utility: Execute before actions, turn off Csrf security for ajax request
     * Author: Pradeep Chaurasia
     * Created Date: 15/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 15/Feb/2018
     */
    public function beforeFilter(Event $event) {
        try {
            parent::beforeFilter($event);

            ############### AUTHENTICATION DETAILS ##################
            $auth = array();
            if ($this->request->is('ajax') || $this->request->action == 'status') {
                $this->autoRender = false;
                // for csrf
                $this->eventManager()->off($this->Csrf);
                // for security component
                //$this->Security->config('unlockedActions', [$this->request->action]);
            }
            
            if (!empty($this->Auth->user()['id'])) {
                $auth['auth_id'] = $this->auth_id = $this->Auth->user()['id'];

                $user = $this->AdminUsers->get($this->auth_id, [
                    'fields' => ['AdminUsers.role_id', 'AdminUsers.email', 'AdminUsers.username', 'AdminUsers.first_name', 'AdminUsers.last_name', 'AdminUsers.created_on'],
                    'contain' => []
                ]);
                if (!empty($user)) {
                    $auth['auth_role'] = $this->auth_role = $user->role_id;
                    $auth['auth_email'] = $this->auth_email = $user->email;
                    $auth['auth_username'] = $this->auth_username = $user->username;
                    $auth['auth_user_fullname'] = $this->auth_user_fullname = ucwords($user->first_name . ' ' . $user->last_name);
                    $auth['auth_user_created'] = $this->auth_user_created = $user->created_on;

                    $this->set('auth', $auth);
                }
                $this->Auth->allow();
            }
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
    }

    /*     * *************************************************
      Function Name: index
     * Type: Public function for showing set Notification triggers
     * Author: Pradeep Chaurasia
     * Created Date: 15/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 15/Feb/2018
     * Output: Notification setting page
     * ************************************************* */

    public function index() {
        $notification = $notification_trigger = '';
        $num = 1;
        $requestdt = array();
        try {
            $this->set('title', 'Manage Email & SMS Notification Settings');
            $this->set('heading', 'Manage Email & SMS Notification Settings');
            $this->viewBuilder()->setLayout('admin');
            $notification_trigger = $this->NotificationSettings->newEntity();
            
            if ($this->request->is(['patch', 'post', 'put'])) {
                $query = $this->NotificationSettings->find('list');
                $notification_trigger = $query->toArray();
                if ($notification_trigger) {
                    $id = key($notification_trigger);
                    $notification_trigger = $this->NotificationSettings->get($id, [
                        'contain' => []
                    ]);
                }
                $requestdt = $this->request->getData();
                $requestdts = array();
                //pr($requestdt); die;
                foreach ($requestdt as $key => $value) {
                    if ($value) {
                        $requestdts[$key] = implode(',', $value);
                    }else{
                        $requestdts[$key] = '';
                    }
                }

                $notification_trigger = $this->NotificationSettings->patchEntity($notification_trigger, $requestdts);
                
                if (!$notification_trigger->errors()) {
                    $ip = $this->Common->getUserIP();
                    $notification_trigger->modified_by = $this->auth_id;
                    $notification_trigger->modified = date('Y-m-d h:i:s');
                    $notification_trigger->modified_from_ip = $ip;
                    if ($this->NotificationSettings->save($notification_trigger)) {
                        $this->Flash->success(__($this->lang_notification_vars['Success']['SUC001']));

                        return $this->redirect(['action' => 'index']);
                    }
                    $this->Flash->error(__($this->lang_notification_vars['Error']['Other']['ER001']));
                }
            }
            $conditions = array();
            $this->paginate = [
                'contain' => [],
                'conditions' => $conditions,
                'order' => ['NotificationSettings.id' => 'desc'],
                'limit' => $num
            ];
            $notification       = $this->paginate($this->NotificationSettings);
            $data               = $notification->toArray();
            $new_registration   = explode(',', $data[0]['new_registration']);
            $new_booking        = explode(',', $data[0]['new_booking']);
            $deposit            = explode(',', $data[0]['deposit']);
            $refund_triggered   = explode(',', $data[0]['refund_triggered']);
            $booking_rescheduled= explode(',', $data[0]['booking_rescheduled']);
            $booking_cancelled  = explode(',', $data[0]['booking_cancelled']);
            $first_new_message  = explode(',', $data[0]['first_new_message']);
            $every_new_message  = explode(',', $data[0]['every_new_message']);
            $penalty_charged    = explode(',', $data[0]['penalty_charged']);
            $new_package_added_host = explode(',', $data[0]['new_package_added_host']);
            $package_deactivated_host = explode(',', $data[0]['package_deactivated_host']);
            $trip_completed     = explode(',', $data[0]['trip_completed']);
            $guest_due_payment  = explode(',', $data[0]['guest_due_payment']);
            $new_review_host    = explode(',', $data[0]['new_review_host']);
            $options = [
                '1' => 'Email',
                '2' => 'Message'
            ];
        } catch (\Exception $e) {            
            $this->Common->saveErrorLog($e);
        }
        $this->set(compact('notification', 'options', 'new_registration', 'new_booking', 'deposit', 'refund_triggered', 'booking_rescheduled', 'booking_cancelled', 'first_new_message', 'every_new_message', 'penalty_charged', 'new_package_added_host', 'package_deactivated_host', 'trip_completed', 'guest_due_payment', 'new_review_host'));
    }

}
