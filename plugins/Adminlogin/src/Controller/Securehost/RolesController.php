<?php
namespace Adminlogin\Controller\Securehost;
use Adminlogin\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;

class RolesController extends AppController
{
    protected $auth_role; //1=>Admin, 2=>Parent
    protected $auth_id;
    protected $auth_email;
    protected $auth_username;
    protected $auth_user_fullname;
    protected $auth_user_created;
    protected $lang_roles_vars;
    
    public function initialize() {
        try {
        parent::initialize();
        $this->loadComponent('Adminlogin.Common');
        $this->loadModel('Adminlogin.AdminUsers');
        $this->loadComponent('Adminlogin.FolderAndFile');
        $this->Common->loadLangFile(['Role']);
        $this->lang_roles_vars = Configure::read('Role');
        $this->set('lang_roles_vars', $this->lang_roles_vars);        
        $this->viewBuilder()->helpers(['Adminlogin.Breadcrumbs']);

          } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
    }
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
     * Type: Public function for Role list
     * Author: Pradeep Singh
     * Created Date: 19/July/2017
     * Modified By: Pradeep Singh
     * Modified Date: 19/July/2017
     * Output: show list of roles
      ***************************************************/
    public function index()
    {
        $num = $pageArr = $data = '';
        try{
            $this->set('title', 'Manage Roles');      
            $this->set('heading', 'Role List');

            $this->viewBuilder()->setLayout('admin');

            $num = (!empty($this->request->query('num')))?trim($this->request->query('num')):10;
            $page = (!empty($this->request->query('page')))?trim($this->request->query('page')):"";
            $pageArr = array('10','25','50','100');

            $conditions = array();  
            $conditions['is_admin'] = 1;
            $conditions['is_superadmin <>'] = 1;
            $this->paginate = [
                'fields' => ['Roles.id','Roles.name','Roles.description','Roles.created_on','Roles.created_by', 'Roles.status'],
                'conditions' => $conditions,
                'limit' => $num,
                'order' => [
                    'Roles.id' => 'desc'
                ]
            ];        
            $roles = $this->paginate($this->Roles);
            $data = $roles->toArray();

            
        } catch (\Exception $e) {
            pr($e->getMessage());
            die;
            $this->saveErrorLog($e);
        }
        $this->set(compact('num'));
        $this->set(compact('pageArr'));
        $this->set('roles', $data);
    }
    /***************************************************
      Function Name: adminIndex
     * Type: Public function for Role list
     * Author: Farhan Hashmi
     * Created Date: 09/OCT/2017
     * Modified By: Farhan Hashmi
     * Modified Date: 09/OCT/2017
     * Output: show list of admin roles
      ***************************************************/
    public function adminIndex()
    {
        $num = $pageArr = $data = '';
        try{
            $this->set('title', 'Manage Admin Roles');      
            $this->set('heading', 'Admin Role List');

            $this->viewBuilder()->setLayout('datatable');

            $num = (!empty($this->request->query('num')))?trim($this->request->query('num')):10;
            $page = (!empty($this->request->query('page')))?trim($this->request->query('page')):"";
            $pageArr = array('10','25','50','100');

            $conditions = array();  
            $conditions['is_admin'] = 1;
            $this->paginate = [
                'fields' => ['Roles.id','Roles.name','Roles.description','Roles.created_on','Roles.created_by', 'Roles.status'],
                'conditions' => $conditions,
                'limit' => $num,
                'order' => [
                    'Roles.id' => 'asc'
                ]
            ];        
            $roles = $this->paginate($this->Roles);
            $data = $roles->toArray();
            
            $this->set('mainLinkNumber',ADMIN_USER_MAINLINK);
            $this->set('subLinkNumber',ADMIN_USER_ROLES_SUBLINK);
            
        } catch (\Exception $e) {
            $this->saveErrorLog($e);
        }
        $this->set(compact('num'));
        $this->set(compact('pageArr'));
        $this->set('roles', $data);
    }
    /***************************************************
      Function Name: add
     * Type: Public function for add Role
     * Input: To : Role  name, description
     * Author: Pradeep Singh
     * Created Date: 19/July/2017
     * Modified By: Pradeep Singh
     * Modified Date: 19/July/2017
     * Output: on success redirect to role list page
      ***************************************************/
    public function add()
    {
        $role = '';
        try{
            $this->set('title', 'Manage Roles');        
            $this->set('heading', 'Add Role');  
            $this->viewBuilder()->setLayout('form');

            $role = $this->Roles->newEntity();        
            if ($this->request->is('post')) {
                $action = (!empty($this->request->data['action']))?trim($this->request->data['action']):'';
                if ($action == 'validate') {
                    $type = (!empty($this->request->data['type']))?trim($this->request->data['type']):'';
                    switch ($type) {
                        case 'role_name':
                            $conditions = array();
                            $name = (!empty($this->request->data['name'])) ? trim($this->request->data['name']):'';
                            $id = (!empty($this->request->data['id'])) ? trim($this->request->data['id']):'';
                            $response =  '{ "valid": false }';
                            if(!empty($name)){
                                $conditions['Roles.name'] = $name;
                                if($id){
                                    $conditions['Roles.id !='] = $id;
                                }
                                if(!($this->Roles->exists($conditions))){
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
                    $this->request->data['is_admin'] = 1;
                    $role = $this->Roles->patchEntity($role, $this->request->getData());
                    if (!$role->errors()) {
                        $role->created_by = $this->auth_id;
                        if ($this->Roles->save($role)) {
                            $this->Flash->success(__($this->lang_roles_vars['Others']['Success']['SUC001']));
                            return $this->redirect(['action' => 'index']);
                        }
                    }else{
                        $this->Flash->error(__($this->lang_roles_vars['Others']['Error']['ER001']));
                    }       
                }

            }
            
        } catch (\Exception $e) {
            $this->saveErrorLog($e);
        }
       
        $this->set(compact('role'));
    }
    /***************************************************
      Function Name: adminAdd
     * Type: Public function for Role add
     * Author: Farhan Hashmi
     * Created Date: 09/OCT/2017
     * Modified By: Farhan Hashmi
     * Modified Date: 09/OCT/2017
     * Output: show add of admin roles
      ***************************************************/
    public function adminAdd()
    {
 
        $role = '';
        try{
            $this->set('title', 'Manage Admin Roles');        
            $this->set('heading', 'Add Admin Role');  
            $this->viewBuilder()->setLayout('form');

            $role = $this->Roles->newEntity();        
            if ($this->request->is('post')) {
                $action = (!empty($this->request->data['action']))?trim($this->request->data['action']):'';
                if ($action == 'validate') {
                    $type = (!empty($this->request->data['type']))?trim($this->request->data['type']):'';
                    switch ($type) {
                        case 'role_name':
                            $conditions = array();
                            $name = (!empty($this->request->data['name'])) ? trim($this->request->data['name']):'';
                            $id = (!empty($this->request->data['id'])) ? trim($this->request->data['id']):'';
                            $response =  '{ "valid": false }';
                            if(!empty($name)){
                                $conditions['Roles.name'] = $name;
                                $conditions['Roles.is_admin'] = 1;
                                if($id){
                                    $conditions['Roles.id !='] = $id;
                                }
                                if(!($this->Roles->exists($conditions))){
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
                    $role = $this->Roles->patchEntity($role, $this->request->getData());
                    if (!$role->errors()) {
                        $role->created_by = $this->auth_id;
                        $role->is_admin = 1;
                        if ($this->Roles->save($role)) {
                            $this->Flash->success(__($this->lang_roles_vars['Others']['Success']['SUC001']));
                            return $this->redirect(['action' => 'adminIndex']);
                        }
                    }else{
                        $this->Flash->error(__($this->lang_roles_vars['Others']['Error']['ER001']));
                    }       
                }

            }
            
        } catch (\Exception $e) {
            $this->saveErrorLog($e);
        }
        $this->set('mainLinkNumber',ADMIN_USER_MAINLINK);
        $this->set('subLinkNumber',ADMIN_USER_ROLES_SUBLINK);
        $this->set(compact('role'));
    }
    /***************************************************
      Function Name: edit
     * Type: Public function for edit Role
     * Input: To : Role id, name, description
     * Author: Pradeep Singh
     * Created Date: 19/July/2017
     * Modified By: Pradeep Singh
     * Modified Date: 19/July/2017
     * Output: on success redirect to role list page
      ***************************************************/
    public function edit($id = null)
    {
        $role = '';
        try{
            $this->set('title', 'Manage Roles');        
            $this->set('heading', 'Edit Role');  
            $this->viewBuilder()->setLayout('form');

           if(empty($id)){
                $this->Flash->error(__($this->lang_roles_vars['Others']['Error']['ER002']));
                return $this->redirect(['action' => 'index']);
            }
            if(!($this->Roles->exists(['id' => $id]))){
                $this->Flash->error(__($this->lang_roles_vars['Others']['Error']['ER003']));
                return $this->redirect(['action' => 'index']);
            }

            $role = $this->Roles->get($id, [
                'contain' => []
            ]);        
            if ($this->request->is(['patch', 'post', 'put'])) {   
                $role = $this->Roles->patchEntity($role, $this->request->getData());
                if (!$role->errors()) {
                    $role->modified_by = $this->auth_id;
                    if ($this->Roles->save($role)) {
                        $this->Flash->success(__($this->lang_roles_vars['Others']['Success']['SUC002']));
                        return $this->redirect(['action' => 'index']);
                    }
                }else{
                    $this->Flash->error(__($this->lang_roles_vars['Others']['Error']['ER004']));
                }          
            }
        } catch (\Exception $e) {
            $this->saveErrorLog($e);
        }

        $this->set(compact('role'));
        $this->set(compact('id'));
    }
     /***************************************************
      Function Name: adminEdit
     * Type: Public function for Role edit
     * Author: Farhan Hashmi
     * Created Date: 09/OCT/2017
     * Modified By: Farhan Hashmi
     * Modified Date: 09/OCT/2017
     * Output:  edit of admin roles
      ***************************************************/
    public function adminEdit($id = null)
    {
        $role = '';
        try{
            $this->set('title', 'Manage Admin Roles');        
            $this->set('heading', 'Admin Edit Role');  
            $this->viewBuilder()->setLayout('form');

           if(empty($id)){
                $this->Flash->error(__($this->lang_roles_vars['Others']['Error']['ER002']));
                return $this->redirect(['action' => 'index']);
            }
            if(!($this->Roles->exists(['id' => $id]))){
                $this->Flash->error(__($this->lang_roles_vars['Others']['Error']['ER003']));
                return $this->redirect(['action' => 'adminIndex']);
            }

            $role = $this->Roles->get($id, [
                'contain' => []
            ]);        
            if ($this->request->is(['patch', 'post', 'put'])) {   
                $role = $this->Roles->patchEntity($role, $this->request->getData());
                if (!$role->errors()) {
                    $role->modified_by = $this->auth_id;
                    if ($this->Roles->save($role)) {
                        $this->Flash->success(__('Admin Role has been updated'));
                        return $this->redirect(['action' => 'adminIndex']);
                    }
                }else{
                    $this->Flash->error(__($this->lang_roles_vars['Others']['Error']['ER004']));
                }          
            }
        } catch (\Exception $e) {
            $this->saveErrorLog($e);
        }
        $this->set('mainLinkNumber',ADMIN_USER_MAINLINK);
        $this->set('subLinkNumber',ADMIN_USER_ROLES_SUBLINK);
        $this->set(compact('role'));
        $this->set(compact('id'));
    }
    /***************************************************
      Function Name: status
     * Type: Public function for change Role status
     * Input: To : Role id
     * Author: Pradeep Singh
     * Created Date: 19/July/2017
     * Modified By: Pradeep Singh
     * Modified Date: 19/July/2017
     * Output: ajax function with popup confirms
      ***************************************************/
    public function status($id = null, $status = null)
    {
        try{
            if ($this->request->is('ajax')) {
                $id = (!empty($this->request->data['id']))?trim($this->request->data['id']):'';
                $status = (!empty($this->request->data['status']))?trim($this->request->data['status']):'';

                if (!empty($id)) {
                    if($this->Roles->exists(['id' => $id])){
                        $query = $this->Roles->query();
                        if(empty($status))
                            $status = 0;
                        $query->update()
                            ->set(['status' => $status])
                            ->where(['id' => $id]);
                        if ($query->execute()) {
                            if($status==1){
                                echo json_encode(array('status'=>1,'msg'=>$this->lang_roles_vars['Others']['Success']['SUC004']));
                            }else{
                                echo json_encode(array('status'=>2,'msg'=>$this->lang_roles_vars['Others']['Success']['SUC003']));
                            }
                            die;
                        }else{
                            echo json_encode(array('status'=>0,'msg'=>$this->lang_roles_vars['Others']['Error']['ER005']));
                            die;
                        }
                    }else{
                        echo json_encode(array('status'=>0,'msg'=>$this->lang_roles_vars['Others']['Error']['ER003']));
                        die;
                    }
                }else{
                    echo json_encode(array('status'=>0,'msg'=>$this->lang_roles_vars['Others']['Error']['ER002']));
                    die;
                }
            }  
            
        } catch (\Exception $e) {
            $this->saveErrorLog($e);
        }
    }
    /***************************************************
      Function Name: delete
     * Type: Public function for delete Role
     * Input: To : Role id
     * Author: Pradeep Singh
     * Created Date: 19/July/2017
     * Modified By: Pradeep Singh
     * Modified Date: 19/July/2017
     * Output: ajax function with popup confirms
      ***************************************************/
    public function delete($id = null)
    {
        try{
            $this->request->allowMethod(['post', 'delete', 'ajax']);
            if ($this->request->is('ajax')) {
                $id = (!empty($this->request->data['id']))?trim($this->request->data['id']):'';
                if(!$this->Roles->check_association($id))
                {
                    if (!empty($id))
                    {
                        $user = $this->Roles->get($id);
                        if ($this->Roles->delete($user)) {
                            echo json_encode(array('status'=>1,'msg'=>$this->lang_roles_vars['Others']['Success']['SUC005']));
                            die;
                        }else{
                            echo json_encode(array('status'=>0,'msg'=>$this->lang_roles_vars['Others']['Error']['ER006']));
                            die;
                        }
                    }else{
                        echo json_encode(array('status'=>0,'msg'=>$this->lang_roles_vars['Others']['Error']['ER002']));
                        die;
                    }
                }else{
                    echo json_encode(array('status'=>0,'msg'=>'Roles is associated with Users'));
                    die;
                }
            }

        } catch (\Exception $e) {
            pr($e->getMessage()); die;
            $this->saveErrorLog($e);
        }
    }
    public function addpermission()
    {
        if($this->request->is('post'))
        {
            $role_id = $this->request->data['role_id'];
            $this->loadModel('RoleModules');
            $role_module = $this->RoleModules->deleteAll(['role_id' => $role_id]);
            $get_module_id = explode(',', $this->request->data['module_ids']);
            $this->loadModel('AdminModules');
            $get_parent = $this->AdminModules->find('list',[
                        'keyField' => 'parent_id',
                        'valueField' => function ($row) {
                             return $row['id'];
            }])->where(['id in' =>$get_module_id])->toArray();
            
            $get_module_id = array_merge($get_module_id,array_keys($get_parent));

            foreach($get_module_id as $module)
            {
                $this->request->data[] = array(
                    'role_id' => $role_id,
                    'admin_module_id' => $module
                ); 
            }
            if(!empty($this->request->data['module_ids']))
            {
                $entities = $this->RoleModules->newEntities($this->request->data());
                $this->RoleModules->saveMany($entities); 
            }
            $this->Flash->success(__('Permission has been added'));
            exit;
        }
    }
    public function permission($role_id=null)
    {
        $this->set('title', 'Manage Permission');        
        $this->set('heading', 'Add Permission');  
        $this->viewBuilder()->setLayout('popupView');
        $permission = array();
        
        $this->loadModel('AdminModules');
        $admin_madules = $this->AdminModules->find()
                ->select(['id','name','parent_id'])
                ->where(['status' => 1])
                ->toArray();
        $modules = array();
        foreach($admin_madules as $module)
        {
            $modules[] = array(
                'id' => $module->id,
                'parent_id' => $module->parent_id,
                'name' => $module->name
            );
        }

        $tree_view = $this->buildTree($modules, 'parent_id', 'id');
        $this->loadModel('RoleModules');
        $get_permission = $this->RoleModules->find()->select(['id','admin_module_id'])->where(['role_id' => $role_id,'status' => 1])->toArray();
        foreach($get_permission as $perm)
        {
            $permission[] = $perm->admin_module_id;
        }
        $this->set(compact('tree_view','permission','role_id'));
        
    }
    public function buildTree($flat, $pidKey, $idKey = null)
    {
        $grouped = array();
        foreach ($flat as $sub){
            $grouped[$sub[$pidKey]][] = $sub;
        }

        $fnBuilder = function($siblings) use (&$fnBuilder, $grouped, $idKey) {
            foreach ($siblings as $k => $sibling) {
                $id = $sibling[$idKey];
                if(isset($grouped[$id])) {
                    $sibling['children'] = $fnBuilder($grouped[$id]);
                }
                $siblings[$k] = $sibling;
            }

            return $siblings;
        };

        $tree = $fnBuilder($grouped[0]);

        return $tree;
    }
}