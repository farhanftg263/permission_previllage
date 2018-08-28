<?php
namespace Adminlogin\Controller\Securehost;

use Adminlogin\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Configure;

class ErrorLogsController extends AppController {

    protected $lang_error_log_vars;

    /**
     * Function Name: initialize
     * Type: Public
     * Utility: To initialize common settings for whole controller actions
     * Author: Pradeep Chaurasia
     * Created Date: 14/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 4/Feb/2018
     */
    public function initialize() {
        try {
            parent::initialize();
            $this->loadComponent('Adminlogin.Common');
            $this->loadModel('Adminlogin.AdminUsers');
            $this->Common->loadLangFile(['ErrorLog']);
            $this->lang_error_log_vars = Configure::read('ErrorLog');
            $this->set('lang_error_log_vars', $this->lang_error_log_vars);            
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
    }
     /**
     * Function Name: beforeFilter
     * Type: Public
     * Utility: Execute before actions, turn off Csrf security for ajax request
     * Author: Pradeep Chaurasia
     * Created Date: 14/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 14/Feb/2018
     */
    
    public function beforeFilter(Event $event)
    {
        try{
            parent::beforeFilter($event);   

            ############### AUTHENTICATION DETAILS ##################
            $auth = array();
            if ($this->request->is('ajax')) {
                $this->autoRender = false;     
                // for csrf
                $this->eventManager()->off($this->Csrf);
                // for security component
                $this->Security->config('unlockedActions', [$this->request->action]);
            }
            
            if(!empty($this->Auth->user()['id'])){
                $auth['auth_id'] = $this->auth_id = $this->Auth->user()['id'];            

                $user = $this->AdminUsers->get($this->auth_id, [
                    'fields' => ['AdminUsers.role_id','AdminUsers.email','AdminUsers.username','AdminUsers.first_name','AdminUsers.last_name', 'AdminUsers.created_on'],
                    'contain' => []
                ]);
                if(!empty($user)){
                    $auth['auth_role'] = $this->auth_role = $user->role_id;
                    $auth['auth_email'] = $this->auth_email = $user->email;
                    $auth['auth_username'] = $this->auth_username = $user->username;
                    $auth['auth_user_fullname'] = $this->auth_user_fullname = ucwords($user->first_name.' '.$user->last_name);
                    $auth['auth_user_created'] = $this->auth_user_created = $user->created_on;

                    $this->set('auth', $auth);
                }            
                $this->Auth->allow();
            } 
        } catch (\Exception $e) {           
            $this->Common->saveErrorLog($e);
        }
    }

    /**
     * Function Name: beforeRender
     * Type: Public
     * Utility: To initialize common settings before rendering the view and after action logic
     * Author: Pradeep Chaurasia
     * Created Date: 14/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 14/Feb/2018
     */
    public function beforeRender(Event $event) {
        try {
            parent::beforeRender($event);            
            $this->viewBuilder()->setLayout('admin');
            $this->viewBuilder()->helpers(['Adminlogin.Breadcrumbs']);
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
    }

    /**
     * Function Name: index
     * Type: Public
     * Utility: Display list of all User Groups with Paging, Delete links
     * @Params: @page:{page_number}
     * Output:   Fetches error logs data from database
     * Author: Pradeep Chaurasia
     * Created Date: 14/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 14/Feb/2018
     */
    public function index() {
        $errorLogs = $page = '';
        $this->set('title', __('Manage Error Log'));
        $this->set('heading', 'Error Log List');  
        try {
            
            $num = (!empty($this->request->query('num')))?trim($this->request->query('num')):10;
            $page = (!empty($this->request->query('page')))?trim($this->request->query('page')):"";
            $pageArr = array('10','25','50','100');
            $errorLogs = $this->paginate($this->ErrorLogs, ['maxLimit' => $this->_adminConfig['pageLimit'],  'order' => ['id DESC']]);
            $this->paginate = [
                'contain' => [],
                'limit' => $num,
                'order' => [
                    'ErrorLogs.id' => 'desc'
                ]
            ];        
            $errorLogs = $this->paginate($this->ErrorLogs);
            $data = $errorLogs->toArray();//pr($data);die;
            
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }        
        $this->set(compact('errorLogs','num','pageArr'));        
    }

     /***************************************************
      Function Name: delete
     * Type: Public function for delete Error log
     * Input: To : Error log id
     * Author: Pradeep Chaurasia
     * Created Date:  14/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date:  14/Feb/2018
     * Output: ajax function with popup confirms
      ***************************************************/
    public function delete($id = null)
    {
        try{
            $this->request->allowMethod(['post', 'delete', 'ajax']);
            if ($this->request->is('ajax')) {
                $id = (!empty($this->request->data['id']))?trim($this->request->data['id']):'';
                if (!empty($id)) {
                    $error_log = $this->ErrorLogs->get($id);
                    if ($this->ErrorLogs->delete($error_log)) {
                        echo json_encode(array('status'=>1,'msg'=>$this->lang_error_log_vars['Success']['SUC001']));
                        die;
                    }else{
                        echo json_encode(array('status'=>0,'msg'=>$this->lang_error_log_vars['Error']['Other']['ER001']));
                        die;
                    }
                }else{
                    echo json_encode(array('status'=>0,'msg'=>$this->lang_error_log_vars['Error']['Other']['ER002']));
                    die;
                }
            }
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
    }


}
