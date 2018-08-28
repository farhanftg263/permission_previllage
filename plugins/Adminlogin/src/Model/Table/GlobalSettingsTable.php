<?php
namespace Adminlogin\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use Cake\Event\Event;
use DOMDocument;

class GlobalSettingsTable extends Table
{   protected $lang_global_setting_vars;

    public function initialize(array $config)
    { 
        parent::initialize($config); 
        $this->setDisplayField('reference');
        $this->setPrimaryKey('id');        
        $this->addBehavior('Timestamp');
        Configure::load("Adminlogin.language" . DS . 'en' . DS . 'GlobalSetting');
        $this->lang_global_setting_vars = Configure::read('GlobalSetting');
    }   
    
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');
        
        $validator
            ->scalar('reference')
            ->notEmpty('reference', $this->lang_global_setting_vars['Error']['reference']['ER001'])
            ->add('reference', [
                'minLength' => [
                    'rule' => ['minLength', 2],
                    'last' => true,
                    'message' => $this->lang_global_setting_vars['Error']['reference']['ER002']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 100],
                    'message' => $this->lang_global_setting_vars['Error']['reference']['ER003']
                ]
            ])
//            ->add('reference', 'unique', [
//                'rule' => 'validateUnique',
//                'provider' => 'table',
//                'message' => $this->lang_global_setting_vars['Error']['reference']['ER004']
//            ])
            ;        
        $validator
            ->scalar('datatype')
            ->notEmpty('datatype', $this->lang_global_setting_vars['Error']['datatype']['ER001'])
            ->add('datatype', [
                'minLength' => [
                    'rule' => ['minLength', 2],
                    'last' => true,
                    'message' => $this->lang_global_setting_vars['Error']['datatype']['ER002']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 50],
                    'message' => $this->lang_global_setting_vars['Error']['datatype']['ER003']
                ]
            ]);      
        
        $validator
            ->scalar('value')
            ->notEmpty('value', $this->lang_global_setting_vars['Error']['value']['ER001'])
            ->add('value', [
                'minLength' => [
                    'rule' => ['minLength', 1],
                    'last' => true,
                    'message' => $this->lang_global_setting_vars['Error']['value']['ER002']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 100],
                    'message' => $this->lang_global_setting_vars['Error']['value']['ER003']
                ]
            ]);
        
        return $validator;
    }
    
    
}