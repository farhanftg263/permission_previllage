<?php
namespace Adminlogin\Controller\Securehost;
use Adminlogin\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;

class EmailTemplatesController extends AppController
{
    protected $auth_role; //1=>Admin, 2=>Parent
    protected $auth_id;
    protected $auth_email;
    protected $auth_username;
    protected $auth_user_fullname;
    protected $auth_user_created;
    protected $lang_email_templates_vars;
    
    /**
     * Function Name:  initialize
     * Type: Public
     * Utility: To initialize common settings for whole controller actions
     * @param : rows, label, placeholder  
     * Output: on success redirect to admin dashboard.
     * Author: Pradeep Chaurasia
     * Created Date: 09/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 09/Feb/2018
     */

    public function initialize() {
        try {
        parent::initialize();
        $this->loadComponent('Adminlogin.Common');
        $this->loadModel('Adminlogin.AdminUsers');
        $this->loadComponent('Adminlogin.FolderAndFile');
        $this->Common->loadLangFile(['EmailTemplate']);
        $this->lang_email_templates_vars = Configure::read('EmailTemplate');
        $this->set('lang_email_templates_vars', $this->lang_email_templates_vars);
        $this->Auth->allow('emailImage');
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
     * Created Date: 09/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 09/Feb/2018
     */
    
    public function beforeFilter(Event $event)
    {
        try{
            parent::beforeFilter($event);   

            ############### AUTHENTICATION DETAILS ##################
            $auth = array();
            if ($this->request->is('ajax') || $this->request->action == 'emailImage' || $this->request->action == 'status') {
                $this->autoRender = false;     
                // for csrf
                $this->eventManager()->off($this->Csrf);
                // for security component
                //$this->Security->config('unlockedActions', [$this->request->action]);
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
     * Type: Public function for showing Email Template Index Page
     * Author: Pradeep Chaurasia
     * Created Date: 08/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 08/Feb/2018
     * Output: Email Template list page
      ***************************************************/
    public function index()
    {
        $emailTemplates = $num = $search_key = $pageArr = '';
        try{
            $this->set('title', 'Manage Email Templates');      
            $this->set('heading', 'Email Template List');        
            $this->viewBuilder()->setLayout('admin');

            $num = (!empty($this->request->query('num')))?trim($this->request->query('num')):10;
            $page = (!empty($this->request->query('page')))?trim($this->request->query('page')):"";
            $pageArr = array('10','25','50','100');

            $conditions = array();
            $search_key = (!empty($this->request->query('sk')))?trim($this->request->query('sk')):'';

            if(!empty($search_key)){
                $arrET['EmailTemplates.slug Like'] = "%".$search_key."%";
                $arrET['EmailTemplates.title Like'] = "%".$search_key."%";
                $conditions['OR'] = $arrET;
            }
            $conditions['EmailTemplates.is_deleted']=0;

            $this->paginate = [
                'contain' => [],
                'fields' => ['EmailTemplates.id','EmailTemplates.slug','EmailTemplates.title','EmailTemplates.subject','EmailTemplates.message','EmailTemplates.status','EmailTemplates.created_on'],
                'conditions' => $conditions,
                'limit' => $num,
                'order' => [
                    'EmailTemplates.id' => 'desc'
                ]
            ];        
            $emailTemplates = $this->paginate($this->EmailTemplates);
            $data = $emailTemplates->toArray();//pr($data);die;
            
        } catch (\Exception $e) { 
            $this->Common->saveErrorLog($e);
        }        
        $this->set(compact('emailTemplates','num','search_key','pageArr'));         
    }
    /***************************************************
      Function Name: add
     * Type: Public function for add Email Template
     * Input: To : Email Template slug, title, subject, message
     * Author: Pradeep Chaurasia
     * Created Date: 09/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 09/Feb/2018
     * Output: on success redirect to list page
      ***************************************************/
    public function add()
    {
        $email_template = '';
        try{
            $this->set('title', 'Manage Email Templates');        
            $this->set('heading', 'Add Email Template');  
            $this->viewBuilder()->setLayout('form');

            $email_template = $this->EmailTemplates->newEntity();        
            if ($this->request->is('post')) { //  pr($this->request->data);die;     
                $action = (!empty($this->request->data['action']))?trim($this->request->data['action']):'';
                if ($action == 'validate') {
                    $field = (!empty($this->request->data['field']))?trim($this->request->data['field']):'';
                    switch ($field) {
                        case 'slug':
                            $conditions = array();
                            $slug = (!empty($this->request->data['slug'])) ? trim($this->request->data['slug']):'';
                            $id = (!empty($this->request->data['id'])) ? trim($this->request->data['id']):'';
                            $response =  '{ "valid": false }';
                            if(!empty($slug)){
                                $conditions['EmailTemplates.slug'] = $slug;
                                $conditions['EmailTemplates.is_deleted']=0;
                                if($id){
                                    $conditions['EmailTemplates.id !='] = $id;
                                }
                                if(!($this->EmailTemplates->exists($conditions))){
                                    $response =  '{ "valid": true }';
                                }
                            }
                            echo $response;die;
                            break;
                        case 'titile':
                            $conditions = array();
                            $titile = (!empty($this->request->data['titile'])) ? trim($this->request->data['titile']):'';
                            $id = (!empty($this->request->data['id'])) ? trim($this->request->data['id']):'';
                            $response =  '{ "valid": false }';
                            if(!empty($titile)){
                                $conditions['EmailTemplates.titile'] = $titile;
                                if($id){
                                    $conditions['EmailTemplates.id !='] = $id;
                                }
                                if(!($this->EmailTemplates->exists($conditions))){
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
                    $email_template = $this->EmailTemplates->patchEntity($email_template, $this->request->getData());

                    if (!$email_template->errors()) {                
                        $email_template->message = htmlspecialchars($email_template->message);
                        $email_template->created_by = $this->auth_id;                        
                        $email_template->modified_by = $this->auth_id;
                        $email_template->modified_on = date('Y-m-d h:i:s');                        
                        //pr($article);die;
                        if ($this->EmailTemplates->save($email_template)) {
                            $this->Flash->success(__($this->lang_email_templates_vars['Success']['SUC001']));
                            return $this->redirect(['action' => 'index']);
                        }
                    }else{
                        $this->Flash->error(__($this->lang_email_templates_vars['Error']['Other']['ER001']));
                    }     
                }
            }
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
        $this->set(compact('email_template'));
    }
    /***************************************************
      Function Name: edit
     * Type: Public function for change Email Template edit
     * Input: To : Email Template slug, title, subject, message
     * Author: Pradeep Chaurasia
     * Created Date: 09/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 09/Feb/2018
     * Output: on success redirect to list page
      ***************************************************/
    
    public function edit($id = null)
    {
        $emailTemplate = '';
        $this->set('title', __('Edit Email Template'));
        $this->set('heading', 'Edit Email Template');  
        $this->viewBuilder()->setLayout('form');
        try{
            if($this->EmailTemplates->exists(['id' => $id])){
                $emailTemplate = $this->EmailTemplates->get($id, [
                    'contain' => []
                ]);
               
                if(!empty($emailTemplate->message)){                    
                    $emailTemplate->message = html_entity_decode($emailTemplate->message);
                }
                if ($this->request->is(['patch', 'post', 'put'])) {
                    $emailTemplate = $this->EmailTemplates->patchEntity($emailTemplate, $this->request->getData());
                    if (!$emailTemplate->errors()) {
                        $emailTemplate->modified_by = $this->auth_id;
                        $emailTemplate->modified_on = date('Y-m-d h:i:s');                       
                        
                        if ($this->EmailTemplates->save($emailTemplate)) {
                            $this->Flash->success(__($this->lang_email_templates_vars['Success']['SUC002']));

                            return $this->redirect(['action' => 'index']);
                        }
                        $this->Flash->error(__($this->lang_email_templates_vars['Error']['Other']['ER002']));
                    }            
                }
            }else{
                $this->Flash->error(__($this->lang_email_templates_vars['Error']['Other']['ER003']));
                return $this->redirect(['action' => 'index']);
            }
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
        
        $this->set(compact('emailTemplate', 'id'));
        $this->set('_serialize', ['emailTemplate']);
    }
    /***************************************************
      Function Name: status
     * Type: Public function for change Email Template status
     * Input: To : Email Template id
     * Author: Pradeep Chaurasia
     * Created Date: 09/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 09/Feb/2018
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
                    if($this->EmailTemplates->exists(['id' => $id])){
                        
                        $query = $this->EmailTemplates->query();
                        $query->update()
                            ->set(['status' => $status])
                            ->where(['id' => $id]);                     
                        if ($query->execute()) { 
                            if($status==1){                                
                                echo json_encode(array('status'=>1,'msg'=>$this->lang_email_templates_vars['Success']['SUC003']));
                               
                            }else{                                
                                echo json_encode(array('status'=>2,'msg'=>$this->lang_email_templates_vars['Success']['SUC004']));
                                
                            }
                              die;
                        }else{ 
                            echo json_encode(array('status'=>0,'msg'=>$this->lang_email_templates_vars['Error']['Other']['ER004']));
                            die;
                            
                        }
                    }else{ 
                         echo json_encode(array('status'=>0,'msg'=>$this->lang_email_templates_vars['Error']['Other']['ER003']));
                        die;                        
                    }
                    
                }else{ 
                   echo json_encode(array('status'=>0,'msg'=>$this->lang_email_templates_vars['Error']['Other']['ER003']));
                    die;                   
                }
            }            
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
    }
    /***************************************************
      Function Name: view
     * Type: Public function for view Email Template
     * Input: To : Email Template id
     * Author: Pradeep Chaurasia
     * Created Date: 09/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 09/Feb/2018
     * Output: ajax function with popup confirms
      ***************************************************/
    
    public function view($id = null)
    {
        $email_template = '';
        $this->set('title', __('Preview'));
        $this->set('heading', 'View Email Template');
        $this->viewBuilder()->setLayout('popupView');
        try{
            if($this->EmailTemplates->exists(['id' => $id])){
                $email_template = $this->EmailTemplates->get($id, [
                    'contain' => []
                ]);
                if(!empty($email_template->message)){                    
                    $email_template->message = html_entity_decode($email_template->message);
                }
            }else{
                $this->Flash->error(__($this->lang_email_templates_vars['Error']['Other']['ER003']));
                return $this->redirect(['action' => 'index']);
            }
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
        
        $this->set('email_template', $email_template);
        $this->set('_serialize', ['email_template']);
    }
    /***************************************************
      Function Name: delete
     * Type: Public function for delete Email Template
     * Input: To : Email Template id
     * Author: Pradeep Chaurasia
     * Created Date:  09/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date:  09/Feb/2018
     * Output: ajax function with popup confirms
      ***************************************************/
    public function delete($id = null)
    {
        try{
            $this->request->allowMethod(['post', 'delete', 'ajax']);
            if ($this->request->is('ajax')) {
                $id = (!empty($this->request->data['id']))?trim($this->request->data['id']):'';
                if (!empty($id)) {
                    $email_template = $this->EmailTemplates->get($id);
                    $query = $this->EmailTemplates->query();
                        $query->update()
                            ->set(['is_deleted' =>1])
                            ->where(['id' => $id]);                     
                    if ($query->execute()) {
                        echo json_encode(array('status'=>1,'msg'=>$this->lang_email_templates_vars['Success']['SUC005']));
                        die;
                    }else{
                        echo json_encode(array('status'=>0,'msg'=>$this->lang_email_templates_vars['Error']['Other']['ER005']));
                        die;
                    }
                }else{
                    echo json_encode(array('status'=>0,'msg'=>$this->lang_email_templates_vars['Error']['Other']['ER003']));
                    die;
                }
            }
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
    }
    public function emailImage(){
        if ($this->request->is(['post','ajax'])) {
            /*******************************************************
            * Only these origins will be allowed to upload images *
            ******************************************************/
            $accepted_origins = ["http://localhost", "http://127.0.0.1"];

            /*********************************************
             * Change this line to set the upload folder *
             *********************************************/
            $imageFolder = "uploads/email_templates/";
            if (!is_dir($imageFolder)) {
                $this->FolderAndFile->__createFolder($imageFolder, 0777);
            }
            reset ($_FILES);
            $temp = current($_FILES);
            if (is_uploaded_file($temp['tmp_name'])){
                if (isset($_SERVER['HTTP_ORIGIN'])) {
                    // same-origin requests won't set an origin. If the origin is set, it must be valid.
                    if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
                        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
                    } else {
                        header("HTTP/1.0 403 Origin Denied");
                        return;
                    }
                }
                
                if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
                    header("HTTP/1.0 500 Invalid file name.");
                    return;
                }
                // Verify extension
                if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "jpeg", "png"))) {
                    header("HTTP/1.0 500 Invalid extension.");
                    return;
                }
                $extension = $this->FolderAndFile->__fileExt($imageFolder.$temp['name']);
                $fileName = $this->FolderAndFile->__fileName($imageFolder.$temp['name']);
                $fname = $this->FolderAndFile->__sanitizeFileName(time().'_'.$fileName).'.'.$extension;
                // Accept upload if there was no origin, or if it is an accepted origin
                $filetowrite = WWW_ROOT.$imageFolder . $fname;
                move_uploaded_file($temp['tmp_name'], $filetowrite);
                // Respond to the successful upload with JSON.
                // Use a location key to specify the path to the saved image resource.
                // { location : '/your/uploaded/image/file'}
                $filetowrite = $this->_adminConfig['baseUrl'].'/uploads/email_templates/'. $fname;
                echo json_encode(array('location' => $filetowrite));
            } else {
                // Notify editor that the upload failed
                header("HTTP/1.0 500 Server Error");
            }
        }
        die; 
   }
}
