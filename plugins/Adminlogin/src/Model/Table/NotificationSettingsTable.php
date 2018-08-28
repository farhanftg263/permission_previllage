<?php
namespace Adminlogin\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use DOMDocument;

class NotificationSettingsTable extends Table
{      
     
    public function initialize(array $config)
    {
        parent::initialize($config);        
       
        $this->setPrimaryKey('id');        
        $this->addBehavior('Timestamp');       
    }
    
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');
        
        return $validator;
    }     
    
}
