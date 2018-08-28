<?php
namespace Adminlogin\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ErrorLogs Model
 *
 * @method \Adminlogin\Model\Entity\ErrorLog get($primaryKey, $options = [])
 * @method \Adminlogin\Model\Entity\ErrorLog newEntity($data = null, array $options = [])
 * @method \Adminlogin\Model\Entity\ErrorLog[] newEntities(array $data, array $options = [])
 * @method \Adminlogin\Model\Entity\ErrorLog|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Adminlogin\Model\Entity\ErrorLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Adminlogin\Model\Entity\ErrorLog[] patchEntities($entities, array $data, array $options = [])
 * @method \Adminlogin\Model\Entity\ErrorLog findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ErrorLogsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('error_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('message')
            ->allowEmpty('message');

        $validator
            ->scalar('file')
            ->allowEmpty('file');

        $validator
            ->scalar('line')
            ->allowEmpty('line');

        $validator
            ->scalar('browser')
            ->allowEmpty('browser');

        $validator
            ->scalar('os')
            ->allowEmpty('os');

        $validator
            ->scalar('referer')
            ->allowEmpty('referer');

        return $validator;
    }
}
