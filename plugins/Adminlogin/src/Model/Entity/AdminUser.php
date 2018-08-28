<?php
namespace Adminlogin\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class AdminUser extends Entity {

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    protected function _setPassword($password) {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher)->hash($password);
        }
    }
    /**
     * Function Name: parentNode
     * Type: Public
     * Utility: essential to create Aro nodes when a new user created
     * Author: Pradeep Rauthan
     * Created Date: 07/Sept/2017
     * Modified By: Pradeep Rauthan
     * Modified Date: 10/Sept/2017
     */
    public function parentNode() {
        if (!$this->id) {
            return null;
        }
        if (isset($this->user_group_id)) {
            $groupId = $this->user_group_id;
        } else {
            $Users = TableRegistry::get('Adminlogin.AdminUsers');
            $user = $Users->find('all', ['fields' => ['user_group_id']])->where(['id' => $this->id])->first();
            $groupId = $user->user_group_id;
        }
        if (!$groupId) {
            return null;
        }
        return ['Adminlogin.UserGroups' => ['id' => $groupId]];

        return null;
    }
}