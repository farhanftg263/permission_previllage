<?php

namespace Adminlogin\Model\Table;
use App\Model\Entity\AdminUser;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use Cake\Auth\DefaultPasswordHasher;

class UsersTable extends Table {

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
        /* $this->belongsTo('Roles')
          ->setForeignKey('role_id')
          ->setJoinType('INNER'); */
        $this->setPrimaryKey('id');
        $this->addBehavior('Adminlogin.UserLog');
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Roles', ['joinTable' => 'user_roles']);
        $this->hasOne('Companies')
                ->setForeignKey('user_id');
        $this->hasMany('UserRoles')
                ->setForeignKey('user_id');
        Configure::load("Adminlogin.language" . DS . 'en' . DS . 'User');
        $this->lang_user_vars = Configure::read('User');
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
    public function beforeSave($event, $entity, $options) {
        // if we get a new password, hash it
        if (!empty($entity->password)) {
            $entity->password = (new DefaultPasswordHasher)->hash($entity->password);
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
                ->scalar('nickname')
                ->notEmpty('nickname', $this->lang_user_vars['Error']['nickname']['ER001'])
                ->add('nickname', [
                    'minLength' => [
                        'rule' => ['minLength', 2],
                        'last' => true,
                        'message' => $this->lang_user_vars['Error']['nickname']['ER002']
                    ],
                    'maxLength' => [
                        'rule' => ['maxLength', 30],
                        'message' => $this->lang_user_vars['Error']['nickname']['ER002']
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
                ->add('email', 'isUniqueEmail', [
                    'rule' => 'isUniqueEmail',
                    'provider' => 'table',
                    'message' => $this->lang_user_vars['Error']['email']['ER005']
        ]);
        $validator
                ->requirePresence('phone', 'create')
                ->notEmpty('phone', $this->lang_user_vars['Error']['phone']['ER001'])
                ->add('phone', [
                    'minLength' => [
                        'rule' => ['minLength', 10],
                        'message' => $this->lang_user_vars['Error']['phone']['ER002']
                    ],
                    'maxLength' => [
                        'rule' => ['maxLength', 12],
                        'message' => $this->lang_user_vars['Error']['phone']['ER002']
                    ],
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
        //$rules->add($rules->isUnique(['username']));
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

    public function isUniqueEmail($value, $context) {
        $conditions = array();
        if (!empty($context['data']['id'])) {
            $conditions['Users.id !='] = $context['data']['id'];
        }
        $conditions['Users.email'] = $context['data']['email'];
        $conditions['Users.is_deleted'] = 0;
        $hasAny = $this->exists($conditions);
        if (!$hasAny) {
            return true;
        }
        return false;
    }

}
