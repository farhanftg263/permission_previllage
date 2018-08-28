<?php
namespace Adminlogin\Controller\Securehost;
use Adminlogin\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;

class CmsPagesController extends AppController
{
    protected $auth_role; //1=>Admin, 2=>Parent
    protected $auth_id;
    protected $auth_email;
    protected $auth_username;
    protected $auth_user_fullname;
    protected $auth_user_created;
    protected $lang_cms_pages_vars;
    
    /**
     * Function Name:  initialize
     * Type: Public
     * Utility: To initialize common settings for whole controller actions
     * @param : rows, label, placeholder  
     * Output: on success redirect to admin dashboard.
     * Author: Pradeep Chaurasia
     * Created Date: 07/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 07/Feb/2018
     */

    public function initialize() {
        try {
        parent::initialize();
        $this->loadComponent('Adminlogin.Common');
        $this->loadModel('Adminlogin.AdminUsers');
        $this->loadComponent('Adminlogin.FolderAndFile');
        $this->Common->loadLangFile(['CmsPage']);
        $this->lang_cms_pages_vars = Configure::read('CmsPage');
        $this->set('lang_cms_pages_vars', $this->lang_cms_pages_vars);
        $this->Auth->allow('pageImage');
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
     * Created Date: 07/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 07/Feb/2018
     */
    
    public function beforeFilter(Event $event)
    {
        try{
            parent::beforeFilter($event);   

            ############### AUTHENTICATION DETAILS ##################
            $auth = array();
            if ($this->request->is('ajax') || $this->request->action == 'pageImage' || $this->request->action == 'status') {
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
     * Type: Public function for showing CMS Page index
     * Author: Pradeep Chaurasia
     * Created Date: 08/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 08/Feb/2018
     * Output: CMS page list page
      ***************************************************/
    public function index()
    {
        $cmsPages = $num = $search_key = $pageArr = '';
        try{
            $this->set('title', 'Manage CMS Pages');      
            $this->set('heading', 'CMS Page List');        
            $this->viewBuilder()->setLayout('admin');

            $num = (!empty($this->request->query('num')))?trim($this->request->query('num')):10;
            $page = (!empty($this->request->query('page')))?trim($this->request->query('page')):"";
            $pageArr = array('10','25','50','100');

            $conditions = array();
            $search_key = (!empty($this->request->query('sk')))?trim($this->request->query('sk')):'';

            if(!empty($search_key)){
                $conditions['CmsPages.page_name Like'] = "%".$search_key."%";
            }
            $conditions['CmsPages.is_deleted']=0;

            $this->paginate = [
                'contain' => [],
                'fields' => ['CmsPages.id','CmsPages.page_name','CmsPages.page_title','CmsPages.meta_keyword','CmsPages.meta_description', 'CmsPages.page_content','CmsPages.status','CmsPages.created_on'],
                'conditions' => $conditions,
                'limit' => $num,
                'order' => [
                    'CmsPages.id' => 'desc'
                ]
            ];        
            $cmsPages = $this->paginate($this->CmsPages);
            $data = $cmsPages->toArray();//pr($data);die;
            
        } catch (\Exception $e) { 
            $this->Common->saveErrorLog($e);
        }
        
        $this->set(compact('cmsPages'));
        $this->set(compact('num'));
        $this->set(compact('search_key'));
        $this->set(compact('pageArr'));        
    }
    /***************************************************
      Function Name: add
     * Type: Public function for change CMS Page add
     * Input: To : CMS Page name, title, meta keyword, meta description, page content
     * Author: Pradeep Chaurasia
     * Created Date: 08/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 08/Feb/2018
     * Output: on success redirect to page list page
      ***************************************************/
    public function add()
    {
        //$cms_page = '';
        try{
            $this->set('title', 'Manage CMS Pages');        
            $this->set('heading', 'Add CMS Page');  
            $this->viewBuilder()->setLayout('form');

            $cms_page = $this->CmsPages->newEntity();        
            if ($this->request->is('post')) {   //pr($this->request->data);die;     
                $action = (!empty($this->request->data['action']))?trim($this->request->data['action']):'';
                if ($action == 'validate') {
                    $field = (!empty($this->request->data['field']))?trim($this->request->data['field']):'';
                    switch ($field) {
                        case 'page_name':
                            $conditions = array();
                            $page_name = (!empty($this->request->data['page_name'])) ? trim($this->request->data['page_name']):'';
                            $id = (!empty($this->request->data['id'])) ? trim($this->request->data['id']):'';
                            $response =  '{ "valid": false }';
                            if(!empty($page_name)){
                                $conditions['CmsPages.page_name'] = $page_name;
                                $conditions['CmsPages.is_deleted']=0;
                                if($id){
                                    $conditions['CmsPages.id !='] = $id;
                                }
                                if(!($this->CmsPages->exists($conditions))){
                                    $response =  '{ "valid": true }';
                                }
                            }
                            echo $response;die;
                            break;
                        case 'page_titile':
                            $conditions = array();
                            $page_titile = (!empty($this->request->data['page_titile'])) ? trim($this->request->data['page_titile']):'';
                            $id = (!empty($this->request->data['id'])) ? trim($this->request->data['id']):'';
                            $response =  '{ "valid": false }';
                            if(!empty($page_titile)){
                                $conditions['CmsPages.page_titile'] = $page_titile;
                                if($id){
                                    $conditions['CmsPages.id !='] = $id;
                                }
                                if(!($this->CmsPages->exists($conditions))){
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
                    $cms_page = $this->CmsPages->patchEntity($cms_page, $this->request->getData());

                    if (!$cms_page->errors()) {                
                        $cms_page->page_content = $cms_page->page_content;
                        $cms_page->page_slug = $this->request->data['page_slug'];
                        $cms_page->created_by = $this->auth_id;                        
                        $cms_page->modified_by = $this->auth_id;
                        $cms_page->modified_on = date('Y-m-d h:i:s');                                            
                        if ($this->CmsPages->save($cms_page)) {
                            $this->Flash->success(__($this->lang_cms_pages_vars['Success']['SUC001']));
                            return $this->redirect(['action' => 'index']);
                        }
                    }else{
                        $this->Flash->error(__($this->lang_cms_pages_vars['Other']['ER001']));
                    }     
                }
            }
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
        $this->set(compact('cms_page'));
    }
    /***************************************************
      Function Name: edit
     * Type: Public function for change CMS Page edit
     * Input: To : CMS Page name, title, meta keyword, meta description, page content
     * Author: Pradeep Chaurasia
     * Created Date: 08/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 08/Feb/2018
     * Output: on success redirect to page list page
      ***************************************************/
    
    public function edit($id = null)
    {
        $cmsPage = '';
        $this->set('title', __('Edit CMS Page'));
        $this->set('heading', 'Edit CMS Page');  
        $this->viewBuilder()->setLayout('form');
        try{
            if($this->CmsPages->exists(['id' => $id])){
                $cmsPage = $this->CmsPages->get($id, [
                    'contain' => []
                ]);
                if(!empty($cmsPage->meta_description)){
                    $cmsPage->meta_description = htmlspecialchars_decode($cmsPage->meta_description);
                }
                if(!empty($cmsPage->meta_keyword)){
                    $cmsPage->meta_keyword = htmlspecialchars_decode($cmsPage->meta_keyword);
                }
                if(!empty($cmsPage->page_content)){                    
                    $cmsPage->page_content = html_entity_decode($cmsPage->page_content);
                }
                if ($this->request->is(['patch', 'post', 'put'])) {
                    $cmsPage = $this->CmsPages->patchEntity($cmsPage, $this->request->getData());
                    if (!$cmsPage->errors()) {                        
                        $cmsPage->modified_by = $this->auth_id;
                        $cmsPage->modified_on = date('Y-m-d h:i:s');
                        if ($this->CmsPages->save($cmsPage)) {
                            $this->Flash->success(__($this->lang_cms_pages_vars['Success']['SUC002']));
                            return $this->redirect(['action' => 'index']);
                        }
                        $this->Flash->error(__($this->lang_cms_pages_vars['Error']['Other']['ER002']));
                    }            
                }
            }else{
                $this->Flash->error(__($this->lang_cms_pages_vars['Error']['Other']['ER003']));
                return $this->redirect(['action' => 'index']);
            }
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
        
        $this->set(compact('cmsPage', 'id'));
        $this->set('_serialize', ['cmsPage']);
    }
    /***************************************************
      Function Name: status
     * Type: Public function for change CMS Page status
     * Input: To : CMS Page id
     * Author: Pradeep Chaurasia
     * Created Date: 08/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 08/Feb/2018
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
                    if($this->CmsPages->exists(['id' => $id])){
                        
                        $query = $this->CmsPages->query();
                        $query->update()
                            ->set(['status' => $status])
                            ->where(['id' => $id]);                     
                        if ($query->execute()) { 
                            if($status==1){                                
                                echo json_encode(array('status'=>1,'msg'=>$this->lang_cms_pages_vars['Success']['SUC003']));
                               
                            }else{                                
                                echo json_encode(array('status'=>2,'msg'=>$this->lang_cms_pages_vars['Success']['SUC004']));
                                
                            }
                              die;
                        }else{ 
                            echo json_encode(array('status'=>0,'msg'=>$this->lang_cms_pages_vars['Error']['Other']['ER004']));
                            die;
                            
                        }
                    }else{ 
                         echo json_encode(array('status'=>0,'msg'=>$this->lang_cms_pages_vars['Error']['Other']['ER003']));
                        die;                        
                    }
                    
                }else{ 
                   echo json_encode(array('status'=>0,'msg'=>$this->lang_cms_pages_vars['Error']['Other']['ER003']));
                    die;                   
                }
            }            
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
    }
    /***************************************************
      Function Name: view
     * Type: Public function for view CMS Page
     * Input: To : CMS Page id
     * Author: Pradeep Chaurasia
     * Created Date: 08/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 08/Feb/2018
     * Output: ajax function with popup confirms
      ***************************************************/
    
    public function view($id = null)
    {
        $cms_page = '';
        $this->set('title', __('Preview'));
        $this->set('heading', 'View CMS Page');
        $this->viewBuilder()->setLayout('popupView');
        try{
            if($this->CmsPages->exists(['id' => $id])){
                $cms_page = $this->CmsPages->get($id, [
                    'contain' => []
                ]);
                if(!empty($cms_page->page_content)){                    
                    $cms_page->page_content = html_entity_decode($cms_page->page_content);
                }
            }else{
                $this->Flash->error(__($this->lang_cms_pages_vars['Error']['Other']['ER003']));
                return $this->redirect(['action' => 'index']);
            }
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }

        $this->set('cms_page', $cms_page);
        $this->set('_serialize', ['cmsPage']);
    }
    /***************************************************
      Function Name: delete
     * Type: Public function for delete CMS Page
     * Input: To : CMS Page id
     * Author: Pradeep Chaurasia
     * Created Date:  08/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date:  08/Feb/2018
     * Output: ajax function with popup confirms
      ***************************************************/
    public function delete($id = null)
    {
        try{
            $this->request->allowMethod(['post', 'delete', 'ajax']);
            if ($this->request->is('ajax')) {
                $id = (!empty($this->request->data['id']))?trim($this->request->data['id']):'';
                if (!empty($id)) {
                    $cms_page = $this->CmsPages->get($id);
                    $query = $this->CmsPages->query();
                        $query->update()
                            ->set(['is_deleted' =>1])
                            ->where(['id' => $id]);                     
                    if ($query->execute()) {                    
                        echo json_encode(array('status'=>1,'msg'=>$this->lang_cms_pages_vars['Success']['SUC005']));
                        die;
                    }else{
                        echo json_encode(array('status'=>0,'msg'=>$this->lang_cms_pages_vars['Error']['Other']['ER005']));
                        die;
                    }
                }else{
                    echo json_encode(array('status'=>0,'msg'=>$this->lang_cms_pages_vars['Error']['Other']['ER003']));
                    die;
                }
            }
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
    }
    public function pageImage(){
        if ($this->request->is(['post','ajax'])) {
            /*******************************************************
            * Only these origins will be allowed to upload images *
            ******************************************************/
            $accepted_origins = ["http://localhost", "http://127.0.0.1"];

            /*********************************************
             * Change this line to set the upload folder *
             *********************************************/
            $imageFolder = "uploads/cms_pages/";
            if (!is_dir($imageFolder)) {
                $this->FolderAndFile->__createFolder($imageFolder, 0755);
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
                $filetowrite = $this->_adminConfig['baseUrl'].'/uploads/cms_pages/'. $fname;
                echo json_encode(array('location' => $filetowrite));
            } else {
                // Notify editor that the upload failed
                header("HTTP/1.0 500 Server Error");
            }
        }
        die; 
   }

   public function searchPageName(){
        if($this->request->is('ajax')){
            $this->autoRender = false;
            $PagesName = $this->CmsPages->find('list',[
              'keyField' => ['page_name'],
              'valueField' => ['page_name']
              ])->where(['page_name LIKE' => '%'.$_GET['term'].'%']);
            if(!empty($PagesName)){
              echo json_encode($PagesName);
            }else{
              echo 'Record not found';
            }
        } 
    }
}
