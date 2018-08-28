<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.3.4
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Adminlogin\Controller\Securehost;

use Adminlogin\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;

/**
 * Error Handling Controller
 *
 * Controller used by ExceptionRenderer to render error responses.
 */
class ErrorController extends AppController
{
    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize()
    {
        $this->loadComponent('RequestHandler');
        $this->viewBuilder()->setLayout(false);
    }

    /**
     * beforeFilter callback.
     *
     * @param \Cake\Event\Event $event Event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeFilter(Event $event)
    {
    }

    /**
     * beforeRender callback.
     *
     * @param \Cake\Event\Event $event Event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        
        //$this->viewBuilder()->setLayout('error');
    }

    /**
     * afterFilter callback.
     *
     * @param \Cake\Event\Event $event Event.
     * @return \Cake\Network\Response|null|void
     */
    public function afterFilter(Event $event)
    {
    }
    public function maintenanceAction(){
        return $this->render('maintenance','infopage');
    }
    //Get error logs
    public function getlogs()
    {
        $this->set('title', 'Error & Exception Logs');      
        $this->set('heading', 'Error List');
        $this->loadModel('ErrorLogs');
        $this->paginate = [
                'fields' => [
                    'ErrorLogs.message',
                    'ErrorLogs.file',
                    'ErrorLogs.line',
                    'ErrorLogs.browser',
                    'ErrorLogs.referer',
                    'ErrorLogs.created',
                ],
                'limit' => 10,
                'order' => [
                    'ErrorLogs.created' => 'DESC'
                ]
            ];        
        $logs = $this->paginate($this->ErrorLogs);
        $this->set(compact('logs'));
    }
     //
    public function save_exception_log($message,$file,$line)
    {
        $this->loadModel('ErrorLogs');
        $data = $this->ErrorLogs->newEntity();
        
        $data->message = $message;
        $data->file = $file;
        $data->line = $line;
        $data->created = date('Y-m-d H:i:s');
        $data->browser = $this->request->header('User-Agent');
        $data->os = $this->RequestHandler->isMobile();
        $data->referer = $this->request->referer();
        
        $this->ErrorLogs->save($data);
        $this->redirect(['controller' => 'error','action' => 'getlogs']);
    }
    public function error401()
    {

        $this->set('title', 'Permission denied');
    }
}
