<?php
namespace Adminlogin\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Core\Configure;

/**
 * FolderAndFile component
 */
class FolderAndFileComponent extends Component
{
    // The other component your component uses
    public $components = [];
    public $controller;
    public $_adminConfig;
    protected $scheme;
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'uploadDir' => 'uploads',
    ];
    public function initialize(array $config){
        $this->controller = $this->_registry->getController();
        $this->_adminConfig = $this->controller->_adminConfig;
        //$this->_adminConfig = Configure::read('Adminlogin.App');  
        //pr($this->_adminConfig);
        //die;
    }
    public function __createFolder($folder, $mode = false){
        if($folder){
            $path = WWW_ROOT.$folder;
            $dir = new Folder($path, true, $mode);
            return true;
        }
        return false;
    }
    public function __chnagePermission($folder, $mode = false, $recursive = false, $exceptions =[]){
        $path = WWW_ROOT.$folder;
        $dir = new Folder();
        $status = $dir->chmod($path, $mode, $recursive, $exceptions);
        return $status;
    }
    public function __copyFolder($options = []){
        if(!empty($options['from']) && !empty($options['to'])){
            $folder1 = WWW_ROOT.$options['from'];
            $folder2 = WWW_ROOT.$options['to'];
            $mode = (!empty($options['mode']))?$options['mode']:0755;
            $folder = new Folder($folder1);
            $rs = $folder->copy([
                'to' => $folder2,
                'from' => $folder1, // Will cause a cd() to occur
                'mode' => $mode,
                'skip' => [],
                'scheme' => Folder::SKIP  // SKIP,MERGE, OVERWRITE
            ]);
            if($rs){
                return true;
            }
        }
        return false;
    }
    public function __deleteFolder($folder){
        $path = WWW_ROOT.$folder;
        $dir = new Folder($path);
        if ($dir->delete()) {
            return true;
        }
        return false;
    }
    public function __findFiles($folder = null, $fileType = '.*\.png'){
        $files = '';
        if($folder){
            $path = WWW_ROOT.$folder;
            // Find all .png in your webroot/img/ folder and sort the results
            $dir = new Folder($path);
            //'.*\.png'
            $files = $dir->find($fileType, true);
        }
        return $files;
    }
    public function __findRecursive($folder = null, $pattern = '(test|index).*'){
        $files = array();
        $path = $this->_adminConfig['adminPath'].DS.$folder;
        $dir = new Folder($path);
        //'(test|index).*'
        $files = $dir->findRecursive($pattern);
        return $files;
    }
    public function __directoryList($folder = null,$sort = true, $exceptions = false, $fullPath = false){
        $path = $this->_adminConfig['adminPath'].DS.$folder;
        $dir = new Folder($path);
        $files = $dir->read($sort, $exceptions, $fullPath);
        return $files;
    }
    public function __currentPath(){
        $dir = new Folder();
        return $dir->pwd();
    }
    ##File Operations
    public function __createFile($filename = null,  $create = false, $mode = 0644){
        if($filename){
            $path = $this->_adminConfig['adminPath'].DS.$filename;
            // Create a new file with 0644 permissions
            $file = new File($path, $create, $mode);
            return $file->path;
        }
        return false;
    }
    public function __deleteFile($filename = null){
        if($filename){
            $path = $this->_adminConfig['adminPath'].DS.$filename;
            // Create a new file with 0644 permissions
            $file = new File($path);
            return $file->delete();
        }
        return false;
    }
    public function __fileExists($filename = null){
        if($filename){
            $path = WWW_ROOT.$filename;
            $file = new File($path);
            return $file->exists();
        }
        return false;
    }
    public function __fileExt($filename = null){
        if($filename){
            $path = WWW_ROOT.$filename;
            $file = new File($path);
            return $file->ext();
        }
        return false;
    }
    public function __fileInfo($filename = null){
        if($filename){
            $path = WWW_ROOT.$filename;
            $file = new File($path);
            return $file->info();
        }
        return false;
    }
    public function __fileName($filename = null){
        if($filename){
            $path = WWW_ROOT.$filename;
            $file = new File($path);
            return $file->name();
        }
        return false;
    }
    public function __fileMimeType($filename = null){
        if($filename){
            $path = WWW_ROOT.$filename;
            $file = new File($path);
            return $file->mime();
        }
        return false;
    }
    public function __sanitizeFileName($dangerous_filename){
        $dangerous_characters = array(" ", '"', "'", "&", "/", "\\", "?", "#");
        // every forbidden character is replace by an underscore
        return str_replace($dangerous_characters, '_', $dangerous_filename);
    }
}
