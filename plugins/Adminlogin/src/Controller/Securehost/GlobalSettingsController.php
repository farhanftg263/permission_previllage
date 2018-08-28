<?php
namespace Adminlogin\Controller\Securehost;
use Adminlogin\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;

class GlobalSettingsController extends AppController
{
    protected $auth_role; //1=>Admin, 2=>Parent
    protected $auth_id;
    protected $auth_email;
    protected $auth_username;
    protected $auth_user_fullname;
    protected $auth_user_created;
    protected $lang_global_setting_vars;
    
    /**
     * Function Name:  initialize
     * Type: Public
     * Utility: To initialize common settings for whole controller actions
     * @param : rows, label, placeholder  
     * Output: on success redirect to listing page.
     * Author: Pradeep Chaurasia
     * Created Date: 19/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 19/Feb/2018
     */

    public function initialize() {
        try {
        parent::initialize();
        $this->loadComponent('Adminlogin.Common');
        $this->loadModel('Adminlogin.AdminUsers');
        $this->loadComponent('Adminlogin.FolderAndFile');
        $this->Common->loadLangFile(['GlobalSetting']);
        $this->lang_global_setting_vars = Configure::read('GlobalSetting');
        $this->set('lang_global_setting_vars', $this->lang_global_setting_vars);        
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
     * Created Date: 19/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 19/Feb/2018
     */
    
    public function beforeFilter(Event $event)
    {
        try{
            parent::beforeFilter($event);   

            ############### AUTHENTICATION DETAILS ##################
            $auth = array();
            if ($this->request->is('ajax') || $this->request->action == 'status') {
                $this->autoRender = false;     
                // for csrf
                $this->eventManager()->off($this->Csrf);
                // for security component
               // $this->Security->config('unlockedActions', [$this->request->action]);
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
    /***************************************************
      Function Name: index
     * Type: Public function for showing Global Setting Page index
     * Author: Pradeep Chaurasia
     * Created Date: 19/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 19/Feb/2018
     * Output: State Licence list page
      ***************************************************/
    public function index()
    {
        $globalSettings = $num = $search_key = $pageArr = '';
        try{
            $this->set('title', 'Manage Global Setting');      
            $this->set('heading', 'Global Setting List');        
            $this->viewBuilder()->setLayout('admin');

            $num = (!empty($this->request->query('num')))?trim($this->request->query('num')):10;
            $page = (!empty($this->request->query('page')))?trim($this->request->query('page')):"";
            $pageArr = array('10','25','50','100');

            $conditions = array();
            $search_key = (!empty($this->request->query('sk')))?trim($this->request->query('sk')):'';

            if(!empty($search_key)){
                $arrET['GlobalSettings.reference Like'] = "%".$search_key."%";                
                $conditions['OR'] = $arrET;
            }
            $conditions['GlobalSettings.is_deleted']=0;

            $this->paginate = [
                'contain' => [],
                'fields' => ['GlobalSettings.id','GlobalSettings.reference','GlobalSettings.datatype','GlobalSettings.value','GlobalSettings.status','GlobalSettings.created_on'],
                'conditions' => $conditions,
                'limit' => $num,
                'order' => [
                    'GlobalSettings.id' => 'desc'
                ]
            ];             
            $globalSettings = $this->paginate($this->GlobalSettings);
          
            $data = $globalSettings->toArray();//pr($data);die;
            
        } catch (\Exception $e) { 
            $this->Common->saveErrorLog($e);
        }        
        $this->set(compact('globalSettings','num','search_key','pageArr'));         
    }
    /***************************************************
      Function Name: add
     * Type: Public function for add Global Setting
     * Input: To : reference, datatype, value
     * Author: Pradeep Chaurasia
     * Created Date: 19/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 19/Feb/2018
     * Output: on success redirect to list page
      ***************************************************/
    public function add()
    {
        $global_setting = '';
        try{
            $this->set('title', 'Manage Global Setting');        
            $this->set('heading', 'Add Global Setting');  
            $this->viewBuilder()->setLayout('form');

            $global_setting = $this->GlobalSettings->newEntity();        
            if ($this->request->is('post')) { //  pr($this->request->data);die;     
                $action = (!empty($this->request->data['action']))?trim($this->request->data['action']):'';
                if ($action == 'validate') {
                    $field = (!empty($this->request->data['field']))?trim($this->request->data['field']):'';
                    switch ($field) {
                        case 'reference':
                            $conditions = array();
                            $reference = (!empty($this->request->data['reference'])) ? trim($this->request->data['reference']):'';
                            $id = (!empty($this->request->data['id'])) ? trim($this->request->data['id']):'';
                            $response =  '{ "valid": false }';
                            if(!empty($reference)){
                                $conditions['GlobalSettings.reference'] = $reference;
                                $conditions['GlobalSettings.is_deleted']=0;
                                if($id){
                                    $conditions['GlobalSettings.id !='] = $id;
                                }
                                if(!($this->GlobalSettings->exists($conditions))){
                                    $response =  '{ "valid": true }';
                                }
                            }
                            echo $response;die;
                            break;                        
                        default:
                            break;
                        die;
                    }
                }else{
                    $global_setting = $this->GlobalSettings->patchEntity($global_setting, $this->request->getData());

                    if (!$global_setting->errors()) {
                        $global_setting->created_by = $this->auth_id;                                       
                        $global_setting->modified_by = $this->auth_id;
                        $global_setting->modified_on = date('Y-m-d h:i:s');
                        if ($this->GlobalSettings->save($global_setting)) {
                            $this->Flash->success(__($this->lang_global_setting_vars['Success']['SUC001']));
                            return $this->redirect(['action' => 'index']);
                        }
                    }else{
                        $this->Flash->error(__($this->lang_global_setting_vars['Error']['Other']['ER001']));
                    }     
                }
            }
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
        $this->set(compact('global_setting'));
    }
    /***************************************************
      Function Name: edit
     * Type: Public function for change Global Setting
     * Input: To : reference, datatype, value
     * Author: Pradeep Chaurasia
     * Created Date: 19/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 19/Feb/2018
     * Output: on success redirect to list page
      ***************************************************/
    
    public function edit($id = null)
    {
        $globalSetting = '';
        $this->set('title', __('Edit Global Setting'));
        $this->set('heading', 'Edit Global Setting');  
        $this->viewBuilder()->setLayout('form');
        try{
            if($this->GlobalSettings->exists(['id' => $id])){
                $globalSetting = $this->GlobalSettings->get($id, [
                    'contain' => []
                ]);
               
                
                if ($this->request->is(['patch', 'post', 'put'])) {
                    $globalSetting = $this->GlobalSettings->patchEntity($globalSetting, $this->request->getData());
                    if (!$globalSetting->errors()) {
                        $globalSetting->modified_by = $this->auth_id;
                        $globalSetting->modified_on = date('Y-m-d h:i:s');
                        if ($this->GlobalSettings->save($globalSetting)) {
                            $this->Flash->success(__($this->lang_global_setting_vars['Success']['SUC002']));

                            return $this->redirect(['action' => 'index']);
                        }
                        $this->Flash->error(__($this->lang_global_setting_vars['Error']['Other']['ER002']));
                    }            
                }
            }else{
                $this->Flash->error(__($this->lang_global_setting_vars['Error']['Other']['ER003']));
                return $this->redirect(['action' => 'index']);
            }
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
        
        $this->set(compact('globalSetting', 'id'));
        $this->set('_serialize', ['globalSetting']);
    }
    /***************************************************
      Function Name: status
     * Type: Public function for change Global Setting status
     * Input: To : Global Setting id
     * Author: Pradeep Chaurasia
     * Created Date: 19/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 19/Feb/2018
     * Output: ajax function with popup confirms
      ***************************************************/
    public function status($id = null, $status = null)
    {
        try{
            if ($this->request->is('ajax')) {
                $id = (!empty($this->request->data['id']))?trim($this->request->data['id']):'';
                $status = (!empty($this->request->data['status']))?trim($this->request->data['status']):'';
                $status=$this->request->data['status'];
                if (!empty($id)) {
                    if($this->GlobalSettings->exists(['id' => $id])){
                        
                        $query = $this->GlobalSettings->query();
                        $query->update()
                            ->set(['status' => $status])
                            ->where(['id' => $id]);                     
                        if ($query->execute()) { 
                            if($status==1){                                
                                echo json_encode(array('status'=>1,'msg'=>$this->lang_global_setting_vars['Success']['SUC003']));
                               
                            }else{                                
                                echo json_encode(array('status'=>2,'msg'=>$this->lang_global_setting_vars['Success']['SUC004']));
                                
                            }
                              die;
                        }else{ 
                            echo json_encode(array('status'=>0,'msg'=>$this->lang_global_setting_vars['Error']['Other']['ER004']));
                            die;
                            
                        }
                    }else{ 
                         echo json_encode(array('status'=>0,'msg'=>$this->lang_global_setting_vars['Error']['Other']['ER003']));
                        die;                        
                    }
                    
                }else{ 
                   echo json_encode(array('status'=>0,'msg'=>$this->lang_global_setting_vars['Error']['Other']['ER003']));
                    die;                   
                }
            }            
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
    }
    /***************************************************
      Function Name: view
     * Type: Public function for view global setting
     * Input: To : Global Setting id
     * Author: Pradeep Chaurasia
     * Created Date: 19/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 19/Feb/2018
     * Output: ajax function with popup confirms
      ***************************************************/
    
    public function view($id = null)
    {
        $global_setting = '';
        $this->set('title', __('Preview'));
        $this->set('heading', 'View Global Setting');
        $this->viewBuilder()->setLayout('popupView');
        try{
            if($this->GlobalSettings->exists(['id' => $id])){
                $global_setting = $this->GlobalSettings->get($id, [
                    'contain' => []
                ]);
            }else{
                $this->Flash->error(__($this->lang_global_setting_vars['Error']['Other']['ER003']));
                return $this->redirect(['action' => 'index']);
            }
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }

        $this->set('global_setting', $global_setting);
        $this->set('_serialize', ['global_setting']);
    }
    /***************************************************
      Function Name: delete
     * Type: Public function for soft delete Global Setting
     * Input: To : Global Setting id
     * Author: Pradeep Chaurasia
     * Created Date:  19/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date:  10/Feb/2018
     * Output: ajax function with popup confirms
      ***************************************************/
    public function delete($id = null)
    {
        try{
            $this->request->allowMethod(['post', 'delete', 'ajax']);
            if ($this->request->is('ajax')) {
                $id = (!empty($this->request->data['id']))?trim($this->request->data['id']):'';
                if (!empty($id)) {                    
                    $query = $this->GlobalSettings->query();
                        $query->update()
                            ->set(['is_deleted' =>1])
                            ->where(['id' => $id]);                     
                    if ($query->execute()) {                    
                        echo json_encode(array('status'=>1,'msg'=>$this->lang_global_setting_vars['Success']['SUC005']));
                        die;
                    }else{
                        echo json_encode(array('status'=>0,'msg'=>$this->lang_global_setting_vars['Error']['Other']['ER005']));
                        die;
                    }
                }else{
                    echo json_encode(array('status'=>0,'msg'=>$this->lang_global_setting_vars['Error']['Other']['ER003']));
                    die;
                }
            }
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
    }    
}
