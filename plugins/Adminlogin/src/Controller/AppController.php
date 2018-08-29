<?php
namespace Adminlogin\Controller;

use App\Controller\AppController as BaseController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Utility\Hash;

class AppController extends BaseController
{
	public $_adminConfig = '';
        public $role_permission;
        
    public function initialize()
    {
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
        $this->loadComponent('Flash');
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        
        $this->loadModel('Users');
        
        $this->loadComponent('Auth', [
            'authorize' => 'Controller',
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email', 'password' => 'password'],
                    'userModel' => 'Adminlogin.AdminUsers',
                    'scope' => ['AdminUsers.status' => 1]
                ],
            ],
            'loginAction' => [
                'controller' => 'AdminUsers',
                'action' => 'login',
                'prefix' => 'securehost',
                'plugin' => 'Adminlogin',
            ],
            'loginRedirect' => [
                'controller' => 'Dashboard',
                'action' => 'index',
                'plugin' => 'Adminlogin',
                'prefix' => 'securehost',
            ],
            'logoutRedirect' => [
                'controller' => 'AdminUsers',
                'action' => 'login',
                'plugin' => 'Adminlogin',
                'prefix' => 'securehost',
            ],
//            'unauthorizedRedirect' => [
//		'controller' => 'Dashboard',
//		'action' => 'index',
//		'plugin' => 'Adminlogin',
//                'prefix' => 'securehost',
//            ],
            'storage' => [
                'className' => 'Session',
                'key' => 'Auth.Admin'
            ],
            'authError' => 'You are not authorized to access that location.',
            'flash' => [
		'element' => 'error'
            ]
        ]);
        $this->_adminConfig = Configure::read('Adminlogin.App');
        $this->set('_adminConfig',$this->_adminConfig);
    }
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        
        $this->Auth->allow(['login','saveErrorLog','forgotpassword','reset']);
              
        
        if(!empty($this->Auth->user()['id']))
        {
            $user = $this->Auth->user();
          
            // Menu
            if(!$this->request->session()->check('menu'))
            {
                $conditions['status'] = 1;
                $conditions['is_superadmin'] = 0;
                if($user['role_id'] == SUPERADMIN)
                {
                    $conditions['is_superadmin'] = 1;
                }
              
                $this->loadModel('AdminModules');
                $admin_madules = $this->AdminModules->find()->select([
                    'id','name','parent_id','title','controller','action','order'
                ])->where($conditions)
                ->toArray();
               
                $modules = array();
                foreach($admin_madules as $module)
                {
                    $modules[] = array(
                        'id' => $module->id,
                        'parent_id' => $module->parent_id,
                        'name' => $module->name,
                        'title' => $module->title,
                        'controller' => $module->controller,
                        'action' => $module->action,
                        'order' => $module->order
                    );
                }
               

                $admin_menu = $this->buildTree($modules, 'parent_id', 'id');
                $admin_menu = Hash::sort($admin_menu, '{n}.order', 'asc');
                $this->request->session()->write('menu',$admin_menu);   
            }else{
                $admin_menu = $this->request->session()->read('menu');
            }
            $this->set(compact('admin_menu'));
            $auth['auth_id'] = $this->Auth->user()['id'];            
            if(!empty($user))
            {
                $auth['auth_role'] = $user['role_id'];
                $auth['auth_email'] = $user['email'];
                $auth['auth_username'] = $user['username'];
                $auth['auth_user_fullname'] = ucwords($user['first_name'].' '.$user['last_name']);
                $auth['auth_user_created'] =  $user['created_by'];
                $this->set('auth', $auth);
            }  
            
            if($user['role_id'] != ADMIN)
            {
                $this->loadModel('RoleModules');
                $get_permission = $this->RoleModules->find()->select(['id','admin_module_id'])->where(['role_id' => $user['role_id'],'status' => 1])->toArray();
                foreach($get_permission as $permission)
                {
                    $this->role_permission[] = $permission->admin_module_id;
                }
              
                if(empty($this->role_permission))
                {
                    //dashboard
                    $this->role_permission = array(16,97);
                }
                $this->set('permission',$this->role_permission);
              
                foreach ($admin_menu as $menu)
                {
                    if(isset($menu['children']))
                    {
                        foreach ($menu['children'] as $link)
                        {
                            if(isset($link['children']))
                            {
                                
                                foreach ($link['children'] as $lk)
                                {

                             
                                    if (strtolower($this->request->params['controller']) == strtolower($lk['controller']) &&
                                    $this->request->params['action'] == $lk['action'])
                                    {
                                        $this->set('title_for_layout', $lk['name']);
                                        if (!in_array($lk['id'],  $this->role_permission))
                                        {
                                            $this->redirect(['controller' => 'error','action' => 'error401']); 
                                            // $logout = Configure::read('Site')['baseUrl'].'/users/logout'; //echo "Invalid Request <a href='$logout'>Logout</a>"; die; 
                                            //throw new NotFoundException(__('Invalid Request'));
                                        }
                                        break;


                                    }
                                }
                            }
                            if (strtolower($this->request->params['controller']) == strtolower($link['controller']) &&
                                    $this->request->params['action'] == $link['action'])
                                    {
                                        $this->set('title_for_layout', $link['name']);
                                        if (!in_array($link['id'],  $this->role_permission))
                                        {
                                            $ref = $this->referer();
						$this->redirect(['controller' => 'error','action' => 'error401','refrer' => $ref]);
                                            //throw new NotFoundException(__('Invalid Request'));
                                        }
                                        break;


                                    }
                        }
                    }
                }
            }
            //pr($admin_menu); exit;  
        } 
        if (!$this->Auth->user()) {
            $this->Auth->config('authError', false);
        }
        $controller = $this->request->params['controller'];
        $action = $this->request->params['action'];
        $this->set(compact('controller','action'));
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
