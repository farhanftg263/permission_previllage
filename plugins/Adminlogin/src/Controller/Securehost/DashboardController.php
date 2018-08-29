<?php
namespace Adminlogin\Controller\Securehost;

use Adminlogin\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

/**
 * Dashboard Controller
 *
 *
 * @method \Adminlogin\Model\Entity\Dashboard[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DashboardController extends AppController
{
	public function initialize() {
        parent::initialize();      
        $this->loadComponent('Adminlogin.Common');
        $this->loadModel('Adminlogin.AdminUsers');        
        //$this->viewBuilder()->helpers(['Adminlogin.Common']);
        $this->viewBuilder()->setLayout('admin');
        $this->viewBuilder()->helpers(['Adminlogin.Breadcrumbs']);
        if (!empty($this->Auth->user('id'))) {
            $this->Auth->allow();
        }
    }
    
     public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);   
        
        ############### AUTHENTICATION DETAILS ##################
        $auth = array();
        if(!empty($this->Auth->user()['id'])){
            $auth['auth_id'] = $this->auth_id = $this->Auth->user()['id'];            
            
         /*  $user = $this->AdminUsers->get($this->auth_id, [
                'fields' => ['Users.role_id','Users.email','Users.username','Users.first_name','Users.last_name', 'Users.created'],
                'contain' => []
            ]); */ 
             $user = $this->AdminUsers->get( $this->Auth->user()['id'], [
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
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {	
	$this->set('title', 'Dashboard');
        $this->set('heading', 'Dashboard');
    }   
    /***************************************************
      Function Name: Message Chat applications
     * Type: Public function for 
     * Author: Farhan
     * Created Date: 26/Jun/2018
     * Modified By: Nishant
     * Modified Date: 26/Jun/2018
     * Output: HTML.
    ***************************************************/
    public function message()
    {
        $this->set('title', 'Manage Message');        
        $this->set('heading', 'Chat');  
        $this->viewBuilder()->setLayout('admin');
    }
    public function ajaxChat()
    {
        //$this->getEventManager()->off($this->Csrf);
        $this->loadComponent('Chat');
        $this->Chat->chat_realtime();
    }
   
}
