<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

class User extends Entity
{
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
    protected $_hidden = [
        'password'
    ];
    protected function _setPassword($password)
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher)->hash($password);
        }
    }
   /* public function parentNode()
    {
            if (!$this->id) {
                    return null;
            }
            if (isset($this->role_id)) {
                    $groupId = $this->role_id;
            } else {
                    $Users = TableRegistry::get('Users');
                    $user = $Users->find('all', ['fields' => ['role_id']])->where(['id' => $this->id])->first();
                    $groupId = $user->role_id;
            }
            if (!$groupId) {
                    return null;
            }
            return ['Roles' => ['id' => $groupId]];
    }*/
}