<?php
namespace Adminlogin\Model\Table;

use App\Model\Entity\UserGroup;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;

class UserGroupsTable extends Table {

    protected $lang_user_groups_vars;

    /**
     * Function Name & Type: Public function initialize
     * Utility: It's used toset table name, display field, primary key &
     * associations, timestamp, ACL behavior, load language file for messages(success/error)
     * Author: Pradeep Rauthan
     * Created Date: 24/August/2017
     * Modified By: Pradeep Singh
     * Modified Date: 30/August/2017
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('cscan_user_groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('AdminUsers', [
            'foreignKey' => 'user_group_id',
            'className' => 'Adminlogin.AdminUsers',
        ]);

        $this->addBehavior('Timestamp');

        $this->addBehavior('Acl.Acl', ['type' => 'requester']);

        Configure::load("Adminlogin.language" . DS . 'en' . DS . 'UserGroup');
        $this->lang_user_groups_vars = Configure::read('UserGroup');
    }

    /**
     * Function Name & Type: Public function validationDefault
     * Utility: To validate all form data
     * Input: id, name, description, status
     * Author: Pradeep Rauthan
     * Created Date: 24/August/2017
     * Modified By: Pradeep Singh
     * Modified Date: 30/August/2017
     */
    public function validationDefault(Validator $validator) {
        $validator
                ->integer('id')
                ->allowEmpty('id', 'create');

        $validator
                ->scalar('name')
                ->notEmpty('name', $this->lang_user_groups_vars['Error']['name']['ER001'])
                ->add('name', [
                    'minLength' => [
                        'rule' => ['minLength', 2],
                        'last' => true,
                        'message' => $this->lang_user_groups_vars['Error']['name']['ER002']
                    ],
                    'maxLength' => [
                        'rule' => ['maxLength', 50],
                        'message' => $this->lang_user_groups_vars['Error']['name']['ER003']
                    ]
                ])
                ->add('name', 'unique', [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => $this->lang_user_groups_vars['Error']['name']['ER004']
        ]);

        $validator
                ->scalar('description')
                ->allowEmpty('description')
                ->add('description', [
                    'minLength' => [
                        'rule' => ['minLength', 2],
                        'last' => true,
                        'message' => $this->lang_user_groups_vars['Error']['description']['ER001']
                    ],
        ]);

        $validator
                ->boolean('status', $this->lang_user_groups_vars['Error']['status']['ER001'])
                ->allowEmpty('status');

        return $validator;
    }

    /**
     * Function Name & Type: Public function buildRules
     * Utility: It's used to define custom rules for validation
     * Author: Pradeep Rauthan
     * Created Date: 24/August/2017
     * Modified By: Pradeep Singh
     * Modified Date: 30/August/2017
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique(['name']));

        return $rules;
    }

}