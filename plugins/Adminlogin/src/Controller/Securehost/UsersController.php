<?php

namespace Adminlogin\Controller\Securehost;

use Adminlogin\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Mailer\MailerAwareTrait;
use Cake\Core\Exception\Exception;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;

class UsersController extends AppController {

    use MailerAwareTrait;

    protected $lang_users_vars;  // for fetch the error lanf file

    /**
     * Function Name:  initialize
     * Type: Public
     * Utility: To initialize common settings for whole controller actions
     * @param : rows, label, placeholder  
     * Output: on success redirect to admin dashboard.
     * Author: Arvind Chaurasia
     * Created Date: 04/Sept/2017
     * Modified By: Pradeep Rauthan
     * Modified Date: 11/Sept/2017
     */

    public function initialize() {
        try {
            parent::initialize();
            $this->loadComponent('Adminlogin.Common');
            $this->loadModel('Adminlogin.Users');
            $this->loadModel('AdminLogin.UserRoles');
            $this->loadModel('Adminlogin.Roles');
            $this->viewBuilder()->helpers(['Adminlogin.Breadcrumbs']);
            if ($this->Auth->user()) {
                $this->Auth->allow('changepassword');
                $this->Auth->allow('logout');
            }

            $this->Common->loadLangFile(['User']);
            $this->lang_users_vars = Configure::read('User');
            $this->set('lang_users_vars', $this->lang_users_vars);
        } catch (\Exception $e) {
            $this->Common->saveErrorLog($e);
        }
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        ############### AUTHENTICATION DETAILS ##################
        $auth = array();
        if (!empty($this->Auth->user()['id'])) {
            $auth['auth_id'] = $this->auth_id = $this->Auth->user()['id'];

            /* $user = $this->AdminUsers->get($this->auth_id, [
              'fields' => ['Users.role_id','Users.email','Users.username','Users.first_name','Users.last_name', 'Users.created'],
              'contain' => []
              ]); */
            $user = $this->Users->get($this->Auth->user()['id'], [
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
    }

    /**
     * Function Name:  Login
     * Type: Public function Login
     * Utility: To identify the user and send to the dashboard page for valid user 
     * @Params: userid and password
     * Output:   create auth session
     * Author: Pradeep Chaurasia
     * Created Date: 06/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 06/Feb/2018
     */
    public function index() {
        $this->loadModel('AdminLogin.Users');
        $this->loadModel('Roles');
        $num = $role_id = $search_key = $data = $roles = $search_key = $pageArr = '';
        try {
            $profile = '';
            $this->set('title', 'User Management');
            $this->set('heading', 'Manage Users');

            $this->viewBuilder()->setLayout('admin');

            $query = $this->Roles->find('list', ['order' => 'Roles.id ASC'])->where(['is_admin' => 1, 'status' => 1]);
            $roles = $query->toArray();

            $num = (!empty($this->request->query('num'))) ? trim($this->request->query('num')) : 10;
            $page = (!empty($this->request->query('page'))) ? trim($this->request->query('page')) : "";
            $pageArr = array('10', '25', '50', '100');

            $conditions = $roleConditions = array();
            $role_id = (!empty($this->request->query('rid'))) ? trim($this->request->query('rid')) : '';
            $search_key = (!empty($this->request->query('sk'))) ? trim($this->request->query('sk')) : '';
            $conditions['Users.role_id Not in'] = array(ADMIN);
            $conditions['Users.is_deleted !='] = 1;
            if (!empty($role_id) || !empty($search_key)) {
                if ($role_id)
                    $conditions['Users.role_id'] = $role_id;
                if ($search_key) {
                    $arrSk['Users.nickname Like'] = '%' . $search_key . '%';
                    $arrSk['Users.first_name Like'] = '%' . $search_key . '%';
                    $arrSk['Users.last_name Like'] = '%' . $search_key . '%';
                    $arrSk['Users.email Like'] = '%' . $search_key . '%';
                    $arrSk['Users.username Like'] = $search_key;

                    $conditions['OR'] = $arrSk;
                }
            }
            if (!empty($this->request->query('profile'))) {
                $profile = $this->request->query('profile');
                $conditions['Users.role_id'] = $profile;
            }
            $this->paginate = [
                'contain' => ['UserRoles' => function($q) {
                        return $q->where(['UserRoles.status' => 1]);
                    }, 'Companies'],
                        'conditions' => $conditions,
                        'limit' => $num,
                        'order' => [
                            'Users.id' => 'desc'
                        ]
                    ];

                    $users = $this->paginate($this->Users);
                    $data = $users->toArray();
                } catch (\Exception $e) {
                    pr($e->getMessage());
                    die;
                    $this->saveErrorLog($e);
                }


                //$usersList = $this->Users->getList();
                $this->set('users', $data);
                $this->set(compact('num', 'usersList'));
                $this->set(compact('role_id'));
                $this->set(compact('roles'));
                $this->set(compact('search_key'));
                $this->set(compact('profile'));
                $this->set(compact('pageArr'));
                $this->set(compact('num'));
            }

            public function edit($id = null) {
                $this->loadModel('Roles');
                $user = $roles = '';
                try {
                    $this->set('title', 'User Management');
                    $this->set('heading', 'Edit User');
                    $this->viewBuilder()->setLayout('form');

                    $query = $this->Roles->find('list', ['order' => 'Roles.id ASC'])->where(['is_admin' => 1]);
                    $roles = $query->toArray();

                    if (empty($id)) {
                        $this->Flash->error(__($this->lang_users_vars['Others']['Error']['ER009']));
                        return $this->redirect(['action' => 'index']);
                    }
                    if (!($this->Users->exists(['id' => $id]))) {
                        $this->Flash->error(__($this->lang_users_vars['Others']['Error']['ER010']));
                        return $this->redirect(['action' => 'index']);
                    }

                    $user = $this->Users->get($id, [
                        'contain' => ['Roles']
                    ]);
                    if ($this->request->is(['patch', 'post', 'put'])) {

                        $user = $this->Users->patchEntity($user, $this->request->getData());
                        //pr($user->errors());die;
                        if ($this->Users->save($user)) {
                            $this->Flash->success(__($this->lang_users_vars['Success']['SUC002']));
                            return $this->redirect(['action' => 'index']);
                        }
                        $this->Flash->error(__($this->lang_users_vars['Error']['email']['ER005']));
                    }
                } catch (\Exception $e) {
                    $this->saveErrorLog($e);
                }

                $this->set(compact('user'));
                $this->set(compact('id'));
                $this->set(compact('roles'));
            }

            public function add() {
                $this->loadModel('Roles');
                $user = $roles = '';
                try {
                    $this->set('title', 'User Management');
                    $this->set('heading', 'Add User');
                    $this->viewBuilder()->setLayout('form');

                    $query = $this->Roles->find('list', ['order' => 'Roles.id ASC'])->where(['is_admin' => 1, 'status' => 1]);
                    $roles = $query->toArray();
                    $user = $this->Users->newEntity();
                    if ($this->request->is('post')) {
                        $action = (!empty($this->request->data['action'])) ? trim($this->request->data['action']) : '';
                        if ($action == 'validate') {
                            $type = (!empty($this->request->data['type'])) ? trim($this->request->data['type']) : '';
                            switch ($type) {
                                case 'email':
                                    $conditions = array();
                                    $email = (!empty($this->request->data['email'])) ? trim($this->request->data['email']) : '';
                                    $id = (!empty($this->request->data['id'])) ? trim($this->request->data['id']) : '';
                                    $response = '{ "valid": false }';
                                    if (!empty($email)) {
                                        $conditions['Users.email'] = $email;
                                        if ($id) {
                                            $conditions['Users.id !='] = $id;
                                        }
                                        if (!($this->Users->exists($conditions))) {
                                            $response = '{ "valid": true }';
                                        }
                                    }
                                    echo $response;
                                    die;
                                    break;
                                case 'username':
                                    $conditions = array();
                                    $username = (!empty($this->request->data['username'])) ? trim($this->request->data['username']) : '';
                                    $id = (!empty($this->request->data['id'])) ? trim($this->request->data['id']) : '';
                                    $response = '{ "valid": false }';
                                    if (!empty($username)) {
                                        $conditions['Users.username'] = $username;
                                        if ($id) {
                                            $conditions['Users.id !='] = $id;
                                        }
                                        if (!($this->Users->exists($conditions))) {
                                            $response = '{ "valid": true }';
                                        }
                                    }
                                    echo $response;
                                    die;
                                    break;
                                //                    case 'password':
                                //                        $conditions = array();
                                //                        $password = (!empty($this->request->data['password'])) ? trim($this->request->data['password']):'';
                                //                        $id = $this->auth_id;
                                //                        $response =  '{ "valid": false }';
                                //                        if(!empty($password)){
                                //                            $conditions['Users.password'] = $password;
                                //                            if($id){
                                //                                $conditions['Users.id !='] = $id;
                                //                            }
                                //                            if($this->Users->exists($conditions)){
                                //                                $response =  '{ "valid": true }';
                                //                            }
                                //                        }
                                //                        echo $response;die;
                                //                        break;
                                default:
                                    break;
                                    die;
                            }
                        } else {
                            
                            $user = $this->Users->patchEntity($user, $this->request->getData());
                            $user->username = $user->email;
                            $user->is_email_verified = 1;
                            $password = $this->Common->generatePassword();
                            $user->password = $password;                          
                            
                                                                   
                            if ($this->request->data['role_id'] == GUEST) {
                                $user->is_guest = 1;
                            } else {
                                $user->is_host = 1;
                            }
                            $user->role_id = $this->request->data['role_id'];
                            if (!$user->errors()) {
                               
                                if ($this->Users->save($user)) {
                                    $roleData = array('user_id' => $user->id,
                                        'role_id' => $this->request->data['role_id']);
                                    $roleEntity = $this->UserRoles->newEntity();
                                    $roleEntity = $this->UserRoles->patchEntity($roleEntity, $roleData);
                                    if ($this->UserRoles->save($roleEntity)) {
                                        ############# Email Notification with Credintials ############
                                        $emailData=array();
                                        $emailData['password']=$password;
                                        $emailData['email']=$user->email; 
                                        $emailData['username']=$user->username;
                                        $emailData['nickname']=$user->nickname;
                                        $emailData['site_url']=SITE_URL;
                                        $emailData['IMAGE_URL']=SITE_URL.FRONTEND_IMG_PATH.'login.png';
                                        $emailUser=  (object)($emailData);
                            
                                        $this->getMailer('Adminlogin.AdminUser')->send('userRegister', [$emailUser]);
                                        $smsData = json_decode(json_encode($user), true);
                                        $options = [
                                                        'email' => $user->email,
                                                        'name' => $user->nickname,
                                                        'role_id' => $this->request->data['role_id']
                                                    ];
                                        $this->getMailer('Adminlogin.AdminUser')->send('sign_up_host_guest', [$options]);
                                        $sms = $this->Common->__sendSMS('USER_REGISTER', $user->phone, $smsData);
                                        $this->Flash->success(__($this->lang_users_vars['Success']['SUC001']));
                                        return $this->redirect(['action' => 'index']);
                                    }
                                }
                            } else {
                                $this->Flash->error(__($this->lang_users_vars['Error']['Other']['ER001']));
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $this->saveErrorLog($e);
                }
                $this->set(compact('user'));
                $this->set(compact('roles'));
            }

            public function profileStatus($id) {
                              
                $this->viewBuilder()->setLayout('popupView');
                $userDetails = $this->Users->get($id, ['contain' => ['UserRoles' => ['Roles']]]);
               
                if ($this->request->is('post')) {
                    $host = $guest = 0;
                    if (!empty($this->request->data['Host'])) {
                        $host = $this->request->data['Host'] == 'active' ? 1 : 0;
                    }
                    if (!empty($this->request->data['Guest'])) {
                        $guest = $this->request->data['Guest'] == 'active' ? 1 : 0;
                    }
                    if (!empty($userDetails->user_roles)) {
                        foreach ($userDetails->user_roles as $role) {
                            if ($role->role->id == GUEST) {
                                $update = $this->UserRoles->updateAll(['status' => $guest], ['user_id' => $id, 'role_id' => GUEST]);
                            } elseif ($role->role->id == HOST) {
                                $this->UserRoles->updateAll(['status' => $host], ['user_id' => $id, 'role_id' => HOST]);
                            }
                        }
                        echo 1;
                        die;
                    } else {
                        echo 0;
                        die;
                    }
                }
                $this->set(compact('userDetails'));
            }

            public function hostinfo($id) {
                $images = [];
                $this->set('title', 'User Management');
                $this->set('heading', 'Host Information');
                $this->viewBuilder()->setLayout('admin');
                $hostInfo = \Cake\ORM\TableRegistry::get('Companies')->
                        find()->select()
                        ->contain(['CompanyImages'])
                        ->where(['user_id' => $id])
                        ->first();
                if (!empty($hostInfo)) {
                    if (!empty($hostInfo->company_images)) {
                        array_walk($hostInfo->company_images, function($val, $key) use (&$images) {
                            $images[] = $val->image;
                        });
                    }
                    $hostInfo->images = $images;
                }


                $this->set(compact('hostInfo'));
            }

            public function status($id = null, $status = null) {
                try {
                    if ($this->request->is('ajax')) {
                        $id = (!empty($this->request->data['id'])) ? trim($this->request->data['id']) : '';
                        $status = (!empty($this->request->data['status'])) ? trim($this->request->data['status']) : '';

                        if (!empty($id)) {
                            if ($this->AdminUsers->exists(['id' => $id])) {
                                $query = $this->AdminUsers->query();
                                if (empty($status))
                                    $status = 0;
                                $query->update()
                                        ->set(['status' => $status])
                                        ->where(['id' => $id]);
                                if ($query->execute()) {
                                    if ($status == 1) {
                                        echo json_encode(array('status' => 1, 'msg' => $this->lang_users_vars['Success']['SUC004']));
                                    } else {
                                        echo json_encode(array('status' => 2, 'msg' => $this->lang_users_vars['Success']['SUC005']));
                                    }
                                    die;
                                    //$user = $this->AdminUsers->get($id);
                                    /* $options = [
                                      'status' => $status,
                                      'name' => $user->first_name.' '.$user->last_name,
                                      'email' => $user->email
                                      ]; */
                                    //$sent = $this->getMailer('User')->send('active_inactive', [$options]);
                                    //die;
                                } else {
                                    echo json_encode(array('status' => 0, 'msg' => $this->lang_users_vars['Others']['Error']['ER011']));
                                    die;
                                }
                            } else {
                                echo json_encode(array('status' => 0, 'msg' => $this->lang_users_vars['Others']['Error']['ER010']));
                                die;
                            }
                        } else {
                            echo json_encode(array('status' => 0, 'msg' => $this->lang_users_vars['Others']['Error']['ER009']));
                            die;
                        }
                    }
                } catch (\Exception $e) {
                    pr($e->getMessage());
                    die;
                    $this->saveErrorLog($e);
                }
            }
            public function blockUnblock($id = null, $status = null)
            {
                try {
                    if ($this->request->is('ajax')) {
                        $id = (!empty($this->request->data['id'])) ? trim($this->request->data['id']) : '';
                        $status = (!empty($this->request->data['is_block'])) ? trim($this->request->data['is_block']) : '';
                        $tile = $status ? 'blocked':'unblocked';
                        if (!empty($id)) {
                            if ($this->Users->exists(['id' => $id])) {
                                $query = $this->Users->query();
                                if (empty($status))
                                    $status = 0;
                                $query->update()
                                        ->set(['is_block' => $status])
                                        ->where(['id' => $id]);
                                if ($query->execute()) 
                                {
                                    $companies = TableRegistry::get('Companies');
                                    $query = $companies->query();
                                    $response_result = $query->update()
                                        ->set(['is_block' => $status])
                                        ->where(['user_id' => $id])
                                        ->execute();
                                    if($response_result)
                                    {
                                        // send email
                                        $user = $this->Users->get($id);
                                        $options = [
                                            'email' => $user->email,
                                            'status' => $status
                                        ];
                                        $sent = $this->getMailer('Adminlogin.AdminUser')->send('block_unblock', [$options]);
                                        echo json_encode(array('status' => 1, 'msg' => "User has been {$tile}"));
                                    }
                                    die;
                                } else {
                                    echo json_encode(array('status' => 0, 'msg' => $this->lang_users_vars['Others']['Error']['ER011']));
                                    die;
                                }
                            } else {
                                echo json_encode(array('status' => 0, 'msg' => $this->lang_users_vars['Others']['Error']['ER010']));
                                die;
                            }
                        } else {
                            echo json_encode(array('status' => 0, 'msg' => $this->lang_users_vars['Others']['Error']['ER009']));
                            die;
                        }
                    }
                } catch (\Exception $e) {
                    pr($e->getMessage());
                    die;
                    $this->saveErrorLog($e);
                }
            }

            public function delete($id = null) {
                try {
                    $this->request->allowMethod(['post', 'delete', 'ajax']);
                    if ($this->request->is('ajax')) {
                        $id = (!empty($this->request->data['id'])) ? trim($this->request->data['id']) : '';
                        if (!empty($id)) {
                            $user = $this->Users->get($id);
                            $query = $this->Users->query();
                            $query->update()
                                    ->set(['is_deleted' => 1])
                                    ->where(['id' => $id]);

                            if ($query->execute()) {
                                // Delete Company
                                $companies = TableRegistry::get('Companies');
                                $query = $companies->query();
                                $response_result = $query->update()
                                    ->set(['is_deleted' => 1])
                                    ->where(['user_id' => $id])
                                    ->execute();
                                
                                
                                echo json_encode(array('status' => 1, 'msg' => $this->lang_users_vars['Success']['SUC003']));
                                die;
                            } else {
                                echo json_encode(array('status' => 0, 'msg' => $this->lang_users_vars['Other']['ER004']));
                                die;
                            }
                        } else {
                            echo json_encode(array('status' => 0, 'msg' => $this->lang_users_vars['Other']['ER003']));
                            die;
                        }
                    }
                } catch (\Exception $e) {
                    $this->saveErrorLog($e);
                }
            }

            public function login() {
                $this->set('title', __('Secure Login'));
                $this->viewBuilder()->setLayout('login');
                try {

                    if ($this->request->is('post')) {
                        $user = $this->Auth->identify();
                        if ($user) {
                            $this->Auth->setUser($user); 
                            return $this->redirect($this->Auth->redirectUrl());
                        }
                        $this->Flash->error(__($this->lang_users_vars['Error']['Other']['ER006']));
                    }
                } catch (\Exception $e) {
                    $this->Common->saveErrorLog($e);
                }
            }

            /**
             * Function Name:  Logout
             * Type: Public function Logout
             * Utility: To destroy the session of the user and send to the user login page
             * @Params: 
             * Output:  Destroy User auth session 
             * Author: Pradeep Chaurasia
             * Created Date: 06/Feb/2018
             * Modified By: Pradeep Chaurasia
             * Modified Date: 06/Feb/2018
             */
            public function logout() {
                if ($this->request->session()->check('menu')) {
                    $this->request->session()->delete('menu');
                }
                $this->Flash->success(__($this->lang_users_vars['Success']['SUC006']));
                return $this->redirect($this->Auth->logout());
            }

            /**
              Function Name: Changepassword
             * Type: Public function to change password
             * Input: To : current password, new password, confirm new password
             * Author: Pradeep Chaurasia
             * Created Date: 07/Feb/2018
             * Modified By: Pradeep Chaurasia
             * Modified Date: 19/Feb/2018
             * Output: on success redirect to dashboard page
             * ************************************************* */
            public function changepassword() {
                $user = '';
                $this->set('title', 'Change Password');
                $this->set('heading', 'Change Password');
                $this->viewBuilder()->setLayout('form');
                try {
                    $user = $this->AdminUsers->get($this->Auth->user('id'), [
                        'contain' => []
                    ]);
                    if ($this->request->is(['patch', 'post', 'put'])) {

                        $user = $this->AdminUsers->patchEntity($user, $this->request->getData());
                        if (!$user->errors()) {
                            if ($this->AdminUsers->save($user)) {
                                $this->Flash->success(__($this->lang_users_vars['Success']['SUC009']));
                                return $this->redirect(['controller' => 'dashboard', 'action' => 'index']);
                            }
                            $this->Flash->error(__($this->lang_users_vars['Error']['ER012']));
                        }
                    }
                } catch (\Exception $e) {
                    $this->Common->saveErrorLog($e);
                }
                $this->set(compact('user'));
            }

            /**
             * Function Name: forgotpassword
             * Type: Public
             * Utility: Send a reset password link with authourized token to users registered e-mail
             * Input: Registered E-mial {email}
             * Output: Success message of e-mail for authourized link
             * Author: Pradeep Chaurasia
             * Created Date: 07/Feb/2018
             * Modified By: Pradeep Chaurasia
             * Modified Date: 07/Feb/2018
             */
            public function forgotpassword() {
                $this->set('title', __('Forgot Password'));
                $this->set('heading', 'Forgot Password');
                $this->viewBuilder()->setLayout('login');
                try {
                    if ($this->request->is('post')) {

                        $email = (!empty($this->request->data['email'])) ? trim($this->request->data['email']) : '';
                        if ($email) {
                            $options = array();
                            $query = $this->AdminUsers->find('all', [
                                'fields' => ['AdminUsers.id', 'AdminUsers.username', 'first_name', 'last_name'],
                                'conditions' => ['AdminUsers.email' => $email, 'AdminUsers.status' => 1]
                            ]);
                            $user = $query->first();

                            if (!empty($user)) {
                                $user_id = $user->id;

                                $attribs['to'] = $email;
                                $options['attribs'] = $attribs;

                                $tokens = $this->Common->__generatePasswordToken();
                                $vars['reset_password_link'] = $this->_adminConfig['baseUrl'] . '/securehost/adminlogin/admin-users/reset/' . $tokens['reset_password_token'];
                                $vars['receiver_name'] = ucwords($user->first_name . ' ' . $user->last_name);
                                $vars['baseUrl'] = $this->_adminConfig['baseUrl'];
                                $vars['subject'] = 'Reset Your Password';
                                $options['vars'] = $vars;

                                $sent = $this->getMailer('Adminlogin.AdminUser')->send('forgotPassword', [$options]);

                                if ($sent) {
                                    $user->reset_password_token = $tokens['reset_password_token'];
                                    $user->token_created_at = $tokens['token_created_at'];
                                    if ($this->AdminUsers->save($user)) {
                                        $this->Flash->success(__($this->lang_users_vars['Success']['SUC007']));
                                        return $this->redirect(['action' => 'forgotpassword']);
                                    }
                                } else {
                                    $this->Flash->error(__($this->lang_users_vars['Error']['Other']['ER008']));
                                }
                            } else {
                                $this->Flash->error(__($this->lang_users_vars['Error']['Other']['ER007']));
                            }
                        } else {
                            $this->Flash->error(__($this->lang_users_vars['Error']['email']['ER001']));
                        }
                    }
                } catch (\Exception $e) {
                    $this->Common->saveErrorLog($e);
                }
            }

            /**
             * Function Name: reset
             * Type: Public
             * Utility: Change user password authourized by password rest token
             * validate 24 hours.
             * @Params: @reset_password_token
             * Input: {new_password, confirm_new_password}
             * Output: Redirect to login page after rest password
             * Author: Pradeep Rauthan
             * Created Date: 12/Sept/2017
             * Modified By: Pradeep Rauthan
             * Modified Date: 12/Sept/2017
             */
            public function reset($token = null) {
                $this->set('title', 'Reset Password');
                $this->set('heading', 'Reset Password');
                $this->viewBuilder()->setLayout('login');
                try {
                    if (!empty($token)) {
                        $query = $this->AdminUsers->find('all', [
                            'fields' => ['AdminUsers.id'],
                            'conditions' => [
                                'AdminUsers.reset_password_token' => $token,
                                'AdminUsers.token_created_at  BETWEEN NOW() -INTERVAL 1 DAY AND NOW()'
                            ]
                        ]);
                        $user = $query->first();
                        if (!empty($user)) {
                            if ($this->request->is(['patch', 'post', 'put'])) {
                                $user = $this->AdminUsers->patchEntity($user, $this->request->getData());
                                $user->reset_password_token = null;
                                $user->token_created_at = null;
                                if ($this->AdminUsers->save($user)) {
                                    $this->Flash->success(__($this->lang_users_vars['Success']['SUC008']));
                                    return $this->redirect(['action' => 'login']);
                                } else {
                                    $this->Flash->error(__($this->lang_users_vars['Error']['Other']['ER011']));
                                }
                            }
                        } else {
                            $this->Flash->error(__($this->lang_users_vars['Error']['Other']['ER010']));
                        }
                    } else {
                        $this->Flash->error(__($this->lang_users_vars['Error']['Other']['ER009']));
                        //return $this->redirect(['action' => 'login']);
                    }
                } catch (\Exception $e) {
                    $this->Common->saveErrorLog($e);
                }
            }

        }
        