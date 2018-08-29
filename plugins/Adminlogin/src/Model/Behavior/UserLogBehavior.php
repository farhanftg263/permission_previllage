<?php

namespace Adminlogin\Model\Behavior;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Utility\Text;

class UserLogBehavior extends Behavior
{
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    { 
        if($entity->id)
        {
            $entity->modified_on  = date('Y-m-d H:i');
            $entity->modified_by = isset($_SESSION['Auth']['User']['id']) 
                       ? $_SESSION['Auth']['User']['id'] : 0;
        }else
        {
            $entity->created_on = date('Y-m-d H:i');
            $entity->created_by = isset($_SESSION['Auth']['User']['id']) 
                       ? $_SESSION['Auth']['User']['id'] : 0;
            $entity->modified_on  = date('Y-m-d H:i');
            $entity->modified_by = isset($_SESSION['Auth']['User']['id']) 
                       ? $_SESSION['Auth']['User']['id'] : 0;
        }
    }
}
