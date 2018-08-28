<?php
namespace Adminlogin\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use DOMDocument;

class EmailTemplatesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        
        $this->setTable('email_templates');
        $this->setDisplayField('slug');
        $this->setPrimaryKey('id');        
        $this->addBehavior('Timestamp');
        Configure::load("Adminlogin.language" . DS . 'en' . DS . 'EmailTemplate');
        $this->lang_email_templates_vars = Configure::read('EmailTemplate');
    }
    public function beforeSave($event, $entity, $options)
    {       
        
        /*if (!empty($entity->message)) { 
            $imageFolder = Configure::read('Adminlogin.App')['baseUrl']."/webroot/uploads/email_templates/";
           
            $entity->message = $this->__replace_img_src($entity->message, $imageFolder,ENV);
            if(strstr(($entity->message),'img src="../../../uploads/email_templates/')){ 
              $entity->message=str_replace('../../../uploads/email_templates/',$imageFolder,$entity->message);          
                
            }
            
            $entity->message = htmlspecialchars($entity->message);
        }*/
        
    }
    
    public function __replace_img_src($img_tag, $new_folder,$env) {         
        $doc = new DOMDocument();
        //$doc->loadHTML($img_tag, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        if($env=='localhost'){ 
            $doc->loadHTML($img_tag, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        }else{ 
            $doc->loadHTML($img_tag);
        }       
        $tags = $doc->getElementsByTagName('img');        
        foreach ($tags as $tag) { 
            $old_src = $tag->getAttribute('src');
            $new_src_url = $new_folder.basename($tag->getAttribute('src'));
          
            $tag->setAttribute('src', $new_src_url);
        }
        return $doc->saveHTML();
    }
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');
        
        $validator
            ->scalar('slug')
            ->notEmpty('slug', $this->lang_email_templates_vars['Error']['slug']['ER001'])
            ->add('slug', [
                'minLength' => [
                    'rule' => ['minLength', 2],
                    'last' => true,
                    'message' => $this->lang_email_templates_vars['Error']['slug']['ER002']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 100],
                    'message' => $this->lang_email_templates_vars['Error']['slug']['ER003']
                ]
            ])
//            ->add('slug', 'unique', [
//                'rule' => 'validateUnique',
//                'provider' => 'table',
//                'message' => $this->lang_email_templates_vars['Error']['slug']['ER004']
//            ])
            ;        
        $validator
            ->scalar('title')
            ->notEmpty('title', $this->lang_email_templates_vars['Error']['title']['ER001'])
            ->add('title', [
                'minLength' => [
                    'rule' => ['minLength', 2],
                    'last' => true,
                    'message' => $this->lang_email_templates_vars['Error']['title']['ER002']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 255],
                    'message' => $this->lang_email_templates_vars['Error']['title']['ER003']
                ]
            ]);      
        
        $validator
            ->scalar('subject')
            ->notEmpty('subject', $this->lang_email_templates_vars['Error']['subject']['ER001'])
            ->add('subject', [
                'minLength' => [
                    'rule' => ['minLength', 2],
                    'last' => true,
                    'message' => $this->lang_email_templates_vars['Error']['subject']['ER002']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 255],
                    'message' => $this->lang_email_templates_vars['Error']['subject']['ER003']
                ]
            ]);
        
        $validator
            ->scalar('message')
            ->notEmpty('message', $this->lang_email_templates_vars['Error']['message']['ER001'])
            ->add('message', [
                'minLength' => [
                    'rule' => ['minLength', 5],
                    'last' => true,
                    'message' => $this->lang_email_templates_vars['Error']['message']['ER002']
                ],
             ]
            );
        
        return $validator;
    }
}