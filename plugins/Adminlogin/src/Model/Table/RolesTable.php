<?php
namespace Adminlogin\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;

class RolesTable extends Table
{
    protected $lang_roles_vars;
    
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->addBehavior('Adminlogin.UserLog');
        $this->hasMany('UserRoles')
           ->setForeignKey('role_id');
        $this->hasMany('Users')
            ->setForeignKey('role_id')
            ->setDependent(true);
        
        $this->addBehavior('Timestamp');
        $this->addBehavior('UserLog');
        //$this->addBehavior('Acl.Acl', ['type' => 'requester']);

        Configure::load("Adminlogin.language" . DS . 'en' . DS . 'Role');
        $this->lang_roles_vars = Configure::read('Role');
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');
        
        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name', $this->lang_roles_vars['RoleError']['name']['ER001'])  
            ->add('name', [
                'minLength' => [
                    'rule' => ['minLength', 2],
                    'last' => true,
                    'message' => $this->lang_roles_vars['RoleError']['name']['ER005']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 30],
                    'message' => $this->lang_roles_vars['RoleError']['name']['ER006']
                ]
            ])
            ->add('name', 'unique', [
                'rule' => 'uniqueValidate',
                'provider' => 'table',
                'message' => $this->lang_roles_vars['RoleError']['name']['ER004']
            ]);
        $validator
            ->allowEmpty('description')  
            ->add('description', [
                'minLength' => [
                    'rule' => ['minLength', 2],
                    'last' => true,
                    'message' => $this->lang_roles_vars['RoleError']['description']['ER001']
                ],
            ]);

        return $validator;
    }
    
    public function uniqueValidate($value, $context) 
    {
        
        $conditions['name'] = $value;
        if(!empty($context['data']['id']))
        {
           $conditions['id <>'] =  $context['data']['id'];
        }
        $conditions['is_admin'] = 1;
        $count = $this->find()->where($conditions)->count();

        return ($count > 0)?false :true; 
    }

    // Vaidate hasMany association
    public function check_association($role_id)
    {
        $count = $this->Users
            ->find('all')
            ->where(['role_id' => $role_id])
            ->count();
        return ($count >0)?true:FALSE;
    }
    public function beforeSave($event, $entity, $options)
    {    
        if ($this->hasField('created_on') && empty($entity->created_on))
        {
            $entity->created_on = date('Y-m-d H:i');
        }
        if ($this->hasField('modified_on'))
        {
            $entity->modified_on = date('Y-m-d H:i');
        }

        //Checks if created_by field is exists and it's not record edit mode
        if ($this->hasField('created_by'))
        {
            $entity->created_by = isset($_SESSION['Auth']['User']['id']) ?
                    $_SESSION['Auth']['User']['id'] : 7;
        }

        //Checks if modified_by field is exists
        if ($this->hasField('modified_by'))
        {
            $entity->modified_by = isset($_SESSION['Auth']['User']['id']) ?
                    $_SESSION['Auth']['User']['id'] : 7;
        }
    }
    //getList
    public function getList()
    {
        $roleList = $this->find('list',[
                    'keyField' => 'id',
                    'valueField' => function ($row) {
                         return $row['name'];
        }]);
        return $roleList->toArray();  
    }
}