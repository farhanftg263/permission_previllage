<?php
namespace Adminlogin\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use DOMDocument;

class CmsPagesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        
        $this->setTable('cms_pages');
        $this->setDisplayField('page_name');
        $this->setPrimaryKey('id');        
        $this->addBehavior('Timestamp');
        Configure::load("Adminlogin.language" . DS . 'en' . DS . 'CmsPage');
        $this->lang_cms_pages_vars = Configure::read('CmsPage');
    }
    public function beforeSave($event, $entity, $options)
    {       
        // if we get a new password, hash it
        /*if (!empty($entity->page_content)) {
            $entity->password = trim($entity->new_password);
            $imageFolder = Configure::read('Adminlogin.App')['baseUrl']."/uploads/cms_pages/"; 
            $entity->page_content = $this->__replace_img_src($entity->page_content, $imageFolder,ENV);
            if(strstr(($entity->page_content),'img src="../../../uploads/cms_pages/')){ 
              $entity->page_content=str_replace('../../../uploads/cms_pages/',$imageFolder,$entity->page_content);      
       
            }
            $entity->page_content = htmlspecialchars($entity->page_content);
        }*/
        
    }
     public function __replace_img_src($img_tag, $new_folder,$env) {         
        $doc = new DOMDocument();
        if($env=='localhost'){
        $doc->loadHTML($img_tag, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        }else{
            $doc->loadHTML($img_tag);
        }
        //$doc->loadHTML($img_tag);
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
            ->scalar('page_name')
            ->notEmpty('page_name', $this->lang_cms_pages_vars['Error']['page_name']['ER001'])
            ->add('page_name', [
                'minLength' => [
                    'rule' => ['minLength', 2],
                    'last' => true,
                    'message' => $this->lang_cms_pages_vars['Error']['page_name']['ER002']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 100],
                    'message' => $this->lang_cms_pages_vars['Error']['page_name']['ER003']
                ]
            ])
//            ->add('page_name', 'unique', [
//                'rule' => 'validateUnique',
//                'provider' => 'table',
//                'message' => $this->lang_cms_pages_vars['Error']['page_name']['ER004']
//            ])
            ;        
        $validator
            ->scalar('page_title')
            ->notEmpty('page_title', $this->lang_cms_pages_vars['Error']['title']['ER001'])
            ->add('page_title', [
                'minLength' => [
                    'rule' => ['minLength', 2],
                    'last' => true,
                    'message' => $this->lang_cms_pages_vars['Error']['title']['ER002']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 100],
                    'message' => $this->lang_cms_pages_vars['Error']['title']['ER003']
                ]
            ]);  

        $validator
            ->scalar('page_slug')
            ->notEmpty('page_slug', $this->lang_cms_pages_vars['Error']['page_slug']['ER001'])
            ->add('page_slug', [
                'minLength' => [
                    'rule' => ['minLength', 2],
                    'last' => true,
                    'message' => $this->lang_cms_pages_vars['Error']['page_slug']['ER002']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 50],
                    'message' => $this->lang_cms_pages_vars['Error']['page_slug']['ER003']
                ]
            ]);    
        
        $validator
            ->scalar('meta_description')
            ->allowEmpty('meta_description')
            ->add('meta_description', [
                'minLength' => [
                    'rule' => ['minLength', 2],
                    'last' => true,
                    'message' => $this->lang_cms_pages_vars['Error']['meta_description']['ER002']
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 500],
                    'message' => $this->lang_cms_pages_vars['Error']['meta_description']['ER003']
                ]
            ])                
//            ->add('meta_description', 'unique', [
//                'rule' => 'validateUnique',
//                'provider' => 'table',
//                'message' => $this->lang_cms_pages_vars['Error']['meta_description']['ER004']
//            ])
            ;
        
        $validator
            ->scalar('page_content')
            ->notEmpty('page_content', $this->lang_cms_pages_vars['Error']['page_content']['ER001'])
            ->add('page_content', [
                'minLength' => [
                    'rule' => ['minLength', 5],
                    'last' => true,
                    'message' => $this->lang_cms_pages_vars['Error']['page_content']['ER002']
                ],
             ]
            );
        
        return $validator;
    }
}