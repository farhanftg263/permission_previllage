<?php
namespace Adminlogin\Model\Table;

use App\Model\Entity\AdminUser;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use Cake\Auth\DefaultPasswordHasher;

class AdminUsersTable extends Table {

    protected $lang_user_vars;
    /**
     * Function Name & Type: Public function initialize
     * Utility: It's used to set table name, display field, primary key &
     * associations, timestamp, load language file for messages(success/error)
     * Author: Pradeep Chaurasia
     * Created Date: 06/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 06/Feb/2018
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->belongsTo('Roles')
                ->setForeignKey('role_id')
                ->setJoinType('INNER');
        $this->setPrimaryKey('id');
        $this->addBehavior('Adminlogin.UserLog');
        $this->addBehavior('Timestamp');
        $this->hasOne('Companies')
           ->setForeignKey('user_id')
           ->setJoinType('INNER');
      /*  $this->belongsTo('UserGroups', [
            'foreignKey' => 'user_group_id',
            'className' => 'Adminlogin.UserGroups',
            'joinType' => 'INNER'
        ]);*/ 
        //Acl behaviour to initiate authourization
       

        Configure::load("Adminlogin.language" . DS . 'en' . DS . 'AdminUser');
        $this->lang_user_vars = Configure::read('AdminUser');
    }
    /**
     * Function Name: beforeSave
     * Type: Public
     * Utility: Perform last minute action before saving the data
     * Author: Pradeep Chaurasia
     * Created Date: 06/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 06/Feb/2018
     */
    public function beforeSave($event, $entity, $options)
    {       
        // if we get a new password, hash it
        if (!empty($entity->new_password)) {
            $entity->password = trim($entity->new_password);
        }
    }
    /**
     * Function Name & Type: Public function validationDefault
     * Utility: To validate all form data
     * Input: username, password, email, confirm password
     * Author: Pradeep Chaurasia
     * Created Date: 06/Feb/2018
     * Modified By: Pradeep Rauthan
     * Modified Date: 06/Feb/2018
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');
        $validator
            ->scalar('username')
            ->notEmpty('username', $this->lang_user_vars['Error']['username']['ER001'])
            ->add('username', [
                'minLength' => [
                    'rule' => ['minLength', 2],
                    'last' => true,
                    'message' => $this->lang_user_vars['Error']['username']['ER002']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 30],
                    'message' => $this->lang_user_vars['Error']['username']['ER003']
                ]
            ])
            ->add('username', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => $this->lang_user_vars['Error']['username']['ER004']
            ]);
        $validator
            ->notEmpty('password', $this->lang_user_vars['Error']['password']['ER001'])
            ->add('password', [
                'minLength' => [
                    'rule' => ['minLength', 5],
                    'message' => $this->lang_user_vars['Error']['password']['ER002']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 30],
                    'message' => $this->lang_user_vars['Error']['password']['ER003']
                ],
            ]);
        $validator
            ->notEmpty('confirm_password', $this->lang_user_vars['Error']['confirm_password']['ER001'])
            ->add('confirm_password', [
                'minLength' => [
                    'rule' => ['minLength', 5],
                    'last' => true,
                    'message' => $this->lang_user_vars['Error']['confirm_password']['ER002']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 30],
                    'message' => $this->lang_user_vars['Error']['confirm_password']['ER003']
                ],
            ])
            ->add('confirm_password', [
                'match' => [
                    'rule' => ['compareWith', 'password'],
                    'message' => $this->lang_user_vars['Error']['confirm_password']['ER004'],
                ]
        ]);
        $validator
            ->requirePresence('email', 'create')
            ->notEmpty('email', $this->lang_user_vars['Error']['email']['ER001'])
            ->add('email', 'validFormat', [
                'rule' => 'email',
                'message' => $this->lang_user_vars['Error']['email']['ER002']
            ])
            ->add('email', [
                'minLength' => [
                    'rule' => ['minLength', 5],
                    'message' => $this->lang_user_vars['Error']['email']['ER003']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 50],
                    'message' => $this->lang_user_vars['Error']['email']['ER004']
                ],                
            ])
            ->add('email', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => $this->lang_user_vars['Error']['email']['ER005']
        ]);
        $validator
            ->notEmpty('current_password', $this->lang_user_vars['Error']['current_password']['ER001'])            
            ->add('current_password', [
                'minLength' => [
                    'rule' => ['minLength', 5],
                    'message' => $this->lang_user_vars['Error']['current_password']['ER002']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 30],
                    'message' => $this->lang_user_vars['Error']['current_password']['ER003']
                ],                
            ])
            ->add('current_password','custom',[
                'rule'=>  function($value, $context){
                    $user = $this->get($context['data']['id']);
                    if ($user) {
                        if ((new DefaultPasswordHasher)->check($value, $user->password)) {
                            return true;
                        }
                    }
                    return false;
                },
                'message'=>$this->lang_user_vars['Error']['current_password']['ER004'],
            ]);
        $validator
            ->notEmpty('new_password', $this->lang_user_vars['Error']['new_password']['ER001'])            
            ->add('new_password', [
                'minLength' => [
                    'rule' => ['minLength', 5],
                    'message' => $this->lang_user_vars['Error']['new_password']['ER002']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 30],
                    'message' => $this->lang_user_vars['Error']['new_password']['ER003']
                ],                
            ]);
        $validator
            ->notEmpty('confirm_new_password', $this->lang_user_vars['Error']['confirm_new_password']['ER001'])            
            ->add('confirm_new_password', [
                'minLength' => [
                    'rule' => ['minLength', 5],
                    'last' => true,
                    'message' => $this->lang_user_vars['Error']['confirm_new_password']['ER002']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 30],
                    'message' => $this->lang_user_vars['Error']['confirm_new_password']['ER003']
                ],
            ])
            ->add('confirm_new_password',[
                'match'=>[
                    'rule'=> ['compareWith','new_password'],
                    'message'=>$this->lang_user_vars['Error']['confirm_new_password']['ER004'],
                ]
            ]);
        return $validator;
    }

    /**
     * Function Name & Type: Public function buildRules
     * Utility: It's used to define custom rules for validation
     * Author: Arvind Chaurasia
     * Created Date: 24/August/2017
     * Modified By: Arvind Chaurasia
     * Modified Date: 5/Sept/2017
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        //$rules->add($rules->existsIn(['user_group_id'], 'UserGroups'));

        return $rules;
    }
    
    //getList
    public function getList() {
        $userList = $this->find('list', [
            'keyField' => 'id',
            'valueField' => function ($row) {
                return $row['first_name'] . ' ' . $row['last_name'];
            }]);
        return $userList->toArray();
    }

}
