<?php
namespace Adminlogin\Model\Entity;

use Cake\ORM\Entity;

/**
 * ErrorLog Entity
 *
 * @property int $id
 * @property string $message
 * @property string $file
 * @property string $line
 * @property string $browser
 * @property string $os
 * @property string $referer
 * @property \Cake\I18n\FrozenTime $created
 */
class ErrorLog extends Entity
{

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
}
