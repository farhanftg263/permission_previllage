<?php

namespace Adminlogin\Controller\Component;

require_once(ROOT . DS . 'vendor' . DS . 'Twilio' . DS . 'autoload.php');
require_once(ROOT . DS . 'vendor' . DS . 'PHPExcel' . DS .'Classes' . DS .'PHPExcel.php');

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Http\RequestHandler;
use Cake\Core\Configure;
use Cake\Utility\Security;
use Twilio\Rest\Client;
use PHPExcel;

/**
 * Common component: incorporates all the common function need to utilize all controllers
 * throughout the admin application
 */
class CommonComponent extends Component {

    protected $_defaultConfig = [];
    public $components = ['Auth', 'Common'];
    private $_con;
    private $controller = null;
    private $session = null;
    private $auth_id = '';

    public function initialize(array $config) {
        parent::initialize($config);
        // Create databse connection instance
        $this->_con = ConnectionManager::get('default');

        $this->controller = $this->_registry->getController();
        //Get Admin auth session
        $this->session = $this->controller->request->session();
        $this->auth_id = $this->session->read('Auth.Admin.id');
    }

    public function saveErrorLog($e) {

        $model = TableRegistry::get('ErrorLogs');
        $data = $model->newEntity();
        $data->message = $e->getMessage();
        $data->file = $e->getFile();
        $data->line = $e->getLine();
        $data->created = date('Y-m-d H:i:s');
        $data->browser = $this->controller->request->header('User-Agent');
        $data->os = $this->controller->request->isMobile();
        $data->referer = $this->controller->request->referer();
        $model->save($data);
    }

    /**
     * Function Name: loadLangFile
     * Type: Public
     * Utility: This function will load success & error messages with there code
     * assembled in a config language directory
     * @Params: array of file names or Entity Model 
     * Output:  Return array of crud column name
     * Author: Pradeep Chaurasia
     * Created Date: 06/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 06/Feb/2018
     */
    public function loadLangFile($file = array()) {
        //$lang = $this->Session->read('language');
        if (empty($lang))
            $lang = 'en';
        if (count($file) > 0) {
            foreach ($file as $val) {
                Configure::load("Adminlogin.language" . DS . 'en' . DS . $val);
            }
        }
    }

    /**
     * Function Name: nextId
     * Type: Public
     * Utility: to get next AUTU_INCREMENT value for any table
     * @Params: table name
     * Output:  Next AUTU_INCREMENT value
     * Author: Pradeep Chaurasia
     * Created Date: 06/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 06/Feb/2018
     */
    public function nextId($table) {
        if ($table) {
            $sql = "SELECT Auto_increment FROM information_schema.tables AS NextId  WHERE table_name='" . $table . "'";
            $stmt = $this->_con->execute($sql);
            $result = $stmt->fetch('obj');
            return $result->Auto_increment;
        }
        return false;
    }
    function exporte($fileName,$headerRow,$data){
        $objPHPExcel = new \PHPExcel();
        $col = 0;
        foreach($headerRow as $header){
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $header);
            $col++;
        }
        $row = 2;
        foreach($data as $d){
            $col = 0;
            foreach($d as $val){
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $val);
                $col++;
            }
            $row++;
        }
        foreach(range('A','Z') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}
        $objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getFont()->setBold(true); //Make heading font bold
        $objPHPExcel->getActiveSheet()->setTitle('Report'); //give title to sheet
        
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;Filename=$fileName.xls");
		header('Cache-Control: max-age=0');
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
    }
    
    function export($fileName, $headerRow, $data) {
 ini_set('max_execution_time', 1600); //increase max_execution_time to 10 min if data set is very large
  $fileContent = implode("\t ", $headerRow)."\n";
  foreach($data as $result) {
   $fileContent .=  implode("\t ", $result)."\n";
  }
 header('Content-type: application/ms-excel'); /// you can set csv format
 header('Content-Disposition: attachment; filename='.$fileName);
 echo $fileContent;
exit;
}
    /**
     * Function Name: __generateToken
     * Type: Public
     * Utility: generate a random string of number with required number of length
     * @Params: length
     * Output:  Return a random string of numbers
     * Author: Pradeep Chaurasia
     * Created Date: 06/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 06/Feb/2018
     */
    public function __generateToken($n) {
        $a = '';
        for ($i = 0; $i < $n; $i++) {
            $a .= mt_rand(0, 9);
        }
        return $a;
    }

    /**
     * Function Name: __generatePasswordToken
     * Type: Public
     * Utility: generate a random encrypted string
     * Output: Return a random string encrypted string
     * Author: Pradeep Chaurasia
     * Created Date: 06/Feb/2018
     * Modified By: Pradeep Chaurasia
     * Modified Date: 06/Feb/2018
     */
    public function __generatePasswordToken() {
        $output = array();
        // Generate a random string 100 chars in length.
        $token = "";
        for ($i = 0; $i < 100; $i++) {
            $d = rand(1, 100000) % 2;
            $d ? $token .= chr(rand(33, 79)) : $token .= chr(rand(80, 126));
        }
        (rand(1, 100000) % 2) ? $token = strrev($token) : $token = $token;
        // Generate hash of random string
        $hash = Security::hash($token, 'sha256', true);
        ;
        for ($i = 0; $i < 20; $i++) {
            $hash = Security::hash($hash, 'sha256', true);
        }
        $output['reset_password_token'] = $hash;
        $output['token_created_at'] = date('Y-m-d H:i:s');
        return $output;
    }

    public function generatePassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 3; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength + 1);
            $pass[] = substr(str_shuffle($alphabet[$n]), 0, 12);
        }
        return implode($pass); //turn the array into a string
    }

    /**
     * Function Name: getUserIP
     * Type: Public
     * Utility: find user ip address 
     * Output: Return user ip address
     * Author: Pradeep Chaurasia
     * Created Date: 25/October/2017
     * Modified By: Pradeep Chaurasia
     * Modified Date: 25/October/2017
     */
    public function getUserIP() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'Unknown IP Address';

        return $ipaddress;
    }

    public function __sendSMS($action, $mobile, $fields = array()) {
        $message = '';
        switch ($action) {
            case 'USER_REGISTER':
                $username = $fields['username'];
                $password = $fields['password'];
                $message = "Welcome to MigrateOutfitter,Login with below crediantials\r\nUsername : " . $username . '\r\nPassword : ' . $password;
                break;
            case 'RESET_PASSWORD':
                $code = $fields['reset_password_token'];
                $message = "Your Reset Password Token is $code";
                break;
            case 'REFERRAL_CODE':
                $code = $fields['referral_code'];
                $link = $fields['referral_link'];
                $message = "Your Referral Code is $code. Please click on link and get sign on bonus $link";
                break;
            default:
                break;
        }
        $client = new Client(SMS_AUTH_ID, SMS_AUTH_TOKEN);
        $response = $client->messages->create(
                // Where to send a text message (your cell phone?)
                '+14185792921', array(
            'from' => '+12012575957',
            'body' => $message
                )
        );
        return $response;

        // Send message
    }

    public function transactionEntry($id, $type, $amount, $des, $data = array()) {
        $description = $this->getTransactionDescription($type, $des, $data);
        $transaction = TableRegistry::get('Transactions');
        $transaction_for = 'Refund';
        $transactionData = array('user_id' => $id,
            'transaction_type' => $type,
            'amount' => $amount,
            'description' => $description,
            'booking_id' => !empty($data['booking_id']) ? $data['booking_id'] : 0,
            'transaction_for' => $transaction_for,
            'data' => json_encode($data));
        $entity = $transaction->newEntity();
        $entity = $transaction->patchEntity($entity, $transactionData);
        $transactionEntry = $transaction->save($entity);
        if ($transactionEntry) {
            return $transactionEntry;
        } else {
            return false;
        }
    }

    public function getTransactionDescription($type, $des, $data = array()) {
        switch ($des) {
            case 'cancel_booking' :
                $description = $type == 'debit' ? 'Refund amount for cancel booking' : 'Refund amount for cancel booking';
                break;
            case 'order' :
                $order_id = !empty($data['order_id']) ? $data['order_id'] : '';
                $description = $type == 'debit' ? 'Amount Debited for Limeslice Order #' . $order_id : 'Amount credited for Limeslice Order #' . $order_id;
                break;
            case 'deposit' :
                $description = $type == 'credit' ? 'Wallet Deposit' : 'Wallet Withdraw';
                break;
            case 'earned' :
                $description = $type == 'earned' ? 'Amount earned from Order #' . $data['order_id'] : '';
                break;
            case 'commission' :
                $description = $type == 'debit' ? 'Amount £' . $data['amount'] . ' debit for Limeslice Commission Order #' . $data['order_id'] : 'Amount ' . $data['amount'] . ' credit for Limeslice Commission Order #' . $data['order_id'];
                break;
            case 'delivery' :
                $description = $type == 'credit' ? 'Amount credited for Order #' . $data['order_id'] . ' as Delivery Fees' : 'Amount debited for Order #' . $data['order_id'] . ' as Delivery Fees';
                break;
            case 'issue' :
                $description = $type == 'credit' ? 'Amount credited from Claim Resolved by Limeslice for order #' . $data['order_id'] : 'Amount debited for claim resolved by limeslice order #' . $data['order_id'];
                break;
            case 'sign_on_bonus' :
                $description = $type == 'credit' ? 'On boarding bonus #' . $data['bonus_amount'] : 'On boarding bonus #' . $data['bonus_amount'];
                break;
            case 'referral_bonus' :
                $description = $type == 'credit' ? 'Amount credited for Referral bonus #' . $data['bonus_amount'] : 'Referral bonus #' . $data['bonus_amount'];
                break;
            case 'cancel_order' :
                $order_id = !empty($data['order_id']) ? $data['order_id'] : '';
                $description = $type == 'debit' ? 'Cancellation Amount Debited for Limeslice Order #' . $order_id : 'Cancellation Amount credited for Limeslice Order #' . $order_id;
                break;
        }

        return $description;
    }

    public function upload($file, $dir, $thumbOptions = array()) {//pr($file);die;
        $image_name = $converted_name = $uploadFile = '';
        $image_name = $file['name'];
        $return = array();
        $flag = false;
        if (!empty($image_name)) {
            $extInfo = explode(".", $image_name);
            $mime = $this->fileMimeType($file['tmp_name']);
            $ext = $this->getExtension($mime);
//$ext = end($extInfo);
            $converted_name = time() . rand() . "." . $ext;
            $uploadFile = $dir . DS . $converted_name;
//pr($uploadFile);die;
            if (in_array($ext, array('jpg', 'jpeg', 'gif'))) {

                $size = $file['size'];
//echo $size;die;
                if ($size > (2 * 1024 * 1024)) {
                    $quality = 100;
                } elseif ($size > (1 * 1024 * 1024)) {
                    $quality = 100;
                } else {
                    $quality = 100;
                }
//$filename = $this->compress_image($file["tmp_name"], $uploadFile,$quality);
                $filename = move_uploaded_file($file['tmp_name'], $uploadFile);
                if ($filename) {
                    $flag = true;
                }
            } elseif (move_uploaded_file($file['tmp_name'], $uploadFile)) {

                $flag = true;
            }
            if ($flag) {
                if (in_array('mobile', $thumbOptions)) {

                    $this->makeThumbnails($dir, $converted_name, 700, 700, 'mobile');
                }
                if (in_array('thumb', $thumbOptions)) {
                    $this->makeThumbnails($dir, $converted_name, 400, 400, 'thumb');
                }

                return $converted_name;
            }
        }
        return null;
    }

    public function fileMimeType($source) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $source);
        finfo_close($finfo);
        return $mime;
    }

    public function getExtension($key, $options = null) {

        $types = array(
            'ai' => 'application/postscript',
            'aif' => 'audio/x-aiff',
            'aifc' => 'audio/x-aiff',
            'aiff' => 'audio/x-aiff',
            'asc' => 'text/plain',
            'atom' => 'application/atom+xml',
            'atom' => 'application/atom+xml',
            'au' => 'audio/basic',
            'avi' => 'video/x-msvideo',
            'bcpio' => 'application/x-bcpio',
            'bin' => 'application/octet-stream',
            'bmp' => 'image/bmp',
            'cdf' => 'application/x-netcdf',
            'cgm' => 'image/cgm',
            'class' => 'application/octet-stream',
            'cpio' => 'application/x-cpio',
            'cpt' => 'application/mac-compactpro',
            'csh' => 'application/x-csh',
            'css' => 'text/css',
            'csv' => 'text/csv',
            'dcr' => 'application/x-director',
            'dir' => 'application/x-director',
            'djv' => 'image/vnd.djvu',
            'djvu' => 'image/vnd.djvu',
            'dll' => 'application/octet-stream',
            'dmg' => 'application/octet-stream',
            'dms' => 'application/octet-stream',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'dtd' => 'application/xml-dtd',
            'dvi' => 'application/x-dvi',
            'dxr' => 'application/x-director',
            'eps' => 'application/postscript',
            'etx' => 'text/x-setext',
            'exe' => 'application/octet-stream',
            'ez' => 'application/andrew-inset',
            'gif' => 'image/gif',
            'gram' => 'application/srgs',
            'grxml' => 'application/srgs+xml',
            'gtar' => 'application/x-gtar',
            'hdf' => 'application/x-hdf',
            'hqx' => 'application/mac-binhex40',
            'htm' => 'text/html',
            'html' => 'text/html',
            'ice' => 'x-conference/x-cooltalk',
            'ico' => 'image/x-icon',
            'ics' => 'text/calendar',
            'ief' => 'image/ief',
            'ifb' => 'text/calendar',
            'iges' => 'model/iges',
            'igs' => 'model/iges',
            //'jpe'     => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            //'jpg'     => 'image/jpeg',
            'js' => 'application/x-javascript',
            'json' => 'application/json',
            'kar' => 'audio/midi',
            'latex' => 'application/x-latex',
            'lha' => 'application/octet-stream',
            'lzh' => 'application/octet-stream',
            'm3u' => 'audio/x-mpegurl',
            'man' => 'application/x-troff-man',
            'mathml' => 'application/mathml+xml',
            'me' => 'application/x-troff-me',
            'mesh' => 'model/mesh',
            'mid' => 'audio/midi',
            'midi' => 'audio/midi',
            'mif' => 'application/vnd.mif',
            'mov' => 'video/quicktime',
            'movie' => 'video/x-sgi-movie',
            'mp2' => 'audio/mpeg',
            'mp3' => 'audio/mpeg',
            'mpe' => 'video/mpeg',
            'mpeg' => 'video/mpeg',
            'mpg' => 'video/mpeg',
            'mpga' => 'audio/mpeg',
            'ms' => 'application/x-troff-ms',
            'msh' => 'model/mesh',
            'mxu' => 'video/vnd.mpegurl',
            'nc' => 'application/x-netcdf',
            'oda' => 'application/oda',
            'ogg' => 'application/ogg',
            'pbm' => 'image/x-portable-bitmap',
            'pdb' => 'chemical/x-pdb',
            'pdf' => 'application/pdf',
            'pgm' => 'image/x-portable-graymap',
            'pgn' => 'application/x-chess-pgn',
            'png' => 'image/png',
            'pnm' => 'image/x-portable-anymap',
            'ppm' => 'image/x-portable-pixmap',
            'ppt' => 'application/vnd.ms-powerpoint',
            'ps' => 'application/postscript',
            'qt' => 'video/quicktime',
            'ra' => 'audio/x-pn-realaudio',
            'ram' => 'audio/x-pn-realaudio',
            'ras' => 'image/x-cmu-raster',
            'rdf' => 'application/rdf+xml',
            'rgb' => 'image/x-rgb',
            'rm' => 'application/vnd.rn-realmedia',
            'roff' => 'application/x-troff',
            'rss' => 'application/rss+xml',
            'rtf' => 'text/rtf',
            'rtx' => 'text/richtext',
            'sgm' => 'text/sgml',
            'sgml' => 'text/sgml',
            'sh' => 'application/x-sh',
            'shar' => 'application/x-shar',
            'silo' => 'model/mesh',
            'sit' => 'application/x-stuffit',
            'skd' => 'application/x-koan',
            'skm' => 'application/x-koan',
            'skp' => 'application/x-koan',
            'skt' => 'application/x-koan',
            'smi' => 'application/smil',
            'smil' => 'application/smil',
            'snd' => 'audio/basic',
            'so' => 'application/octet-stream',
            'spl' => 'application/x-futuresplash',
            'src' => 'application/x-wais-source',
            'sv4cpio' => 'application/x-sv4cpio',
            'sv4crc' => 'application/x-sv4crc',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
            'swf' => 'application/x-shockwave-flash',
            't' => 'application/x-troff',
            'tar' => 'application/x-tar',
            'tcl' => 'application/x-tcl',
            'tex' => 'application/x-tex',
            'texi' => 'application/x-texinfo',
            'texinfo' => 'application/x-texinfo',
            'tif' => 'image/tiff',
            'tiff' => 'image/tiff',
            'tr' => 'application/x-troff',
            'tsv' => 'text/tab-separated-values',
            'txt' => 'text/plain',
            'ustar' => 'application/x-ustar',
            'vcd' => 'application/x-cdlink',
            'vrml' => 'model/vrml',
            'vxml' => 'application/voicexml+xml',
            'wav' => 'audio/x-wav',
            'wbmp' => 'image/vnd.wap.wbmp',
            'wbxml' => 'application/vnd.wap.wbxml',
            'wml' => 'text/vnd.wap.wml',
            'wmlc' => 'application/vnd.wap.wmlc',
            'wmls' => 'text/vnd.wap.wmlscript',
            'wmlsc' => 'application/vnd.wap.wmlscriptc',
            'wrl' => 'model/vrml',
            'xbm' => 'image/x-xbitmap',
            'xht' => 'application/xhtml+xml',
            'xhtml' => 'application/xhtml+xml',
            'xls' => 'application/vnd.ms-excel',
            'xml' => 'application/xml',
            'xpm' => 'image/x-xpixmap',
            'xsl' => 'application/xml',
            'xslt' => 'application/xslt+xml',
            'xul' => 'application/vnd.mozilla.xul+xml',
            'xwd' => 'image/x-xwindowdump',
            'xyz' => 'chemical/x-xyz',
            'zip' => 'application/zip',
            'mp4' => 'video/mp4'
        );
        if ($options == 'mime_type') {
            return $types[$key];
        } else {
            $flipped = array_flip($types);
            return $flipped[$key];
        }
    }
    
    public function insertNotification($user_id, $action, $users = array(), $data = array()) {
        $json_data = json_encode($data);
        $title = $this->_getTitle($action);
        $description = $this->_getDescription($action);
        $notificationData = array(
            'user_id' => $user_id,
            'sender_id' => 1,
            'action' => $action,
            'type' => 'email',
            'title' => $title,
            'description' => $description,
            'json' => $json_data
        );
        pr($notificationData);
        die;
        $pushTable = TableRegistry::get('Notifications');
        $pushEntity = $pushTable->newEntity();
        $pushEntity = $pushTable->patchEntity($pushEntity, $pushData);
        if ($pushTable->save($pushEntity)) {
            $string = '1';
            exec('env MAGICK_THREAD_LIMIT=1');
            exec("/usr/bin/php /var/www/html/SelectTravel/bin/cake.php notification > /dev/null 2>/dev/null &", $string);
            return true;
        }
        return false;
    }
    
    private function _getTitle($action){
        if(!empty($action)){
            switch($action){
                case 'signup' :
                    $title = 'Welcome to Migrate Outfitter';
                    break;
            }
            return $title;
        } 
        return false;
    }
    
    private function _getDescription($action){
        if(!empty($action)){
            switch($action){
                case 'signup' :
                    $description = 'Welcome to Migrate Outfitter,You have successfully registered';
                    break;
            }
            return $description;
        } 
        return false;
    }

    public function makeThumbnails($updir, $img, $thumbnail_width = 150, $thumbnail_height = 150, $thumb_beforeword = "thumb") {

        $arr_image_details = getimagesize("$updir" . DS . "$img"); // pass id to thumb name
        $original_width = $arr_image_details[0];
        $original_height = $arr_image_details[1];


        if ($original_width > $thumbnail_width || $original_height > $thumbnail_height) {
            if ($original_width > $original_height) {
                $new_width = $thumbnail_width;
                $new_height = ($original_height * $new_width) / $original_width;
            } else if ($original_width < $original_height) {
                $new_height = $thumbnail_height;
                $new_width = ($original_width * $new_height) / $original_height;
            } else {
                $new_width = $new_height = $thumbnail_width;
            }
        } else {
            $new_width = $original_width;
            $new_height = $original_height;
        }



        if ($arr_image_details[2] == 1) {
            $imgt = "ImageGIF";
            $imgcreatefrom = "ImageCreateFromGIF";
        }
        if ($arr_image_details[2] == 2) {
            $imgt = "ImageJPEG";
            $imgcreatefrom = "ImageCreateFromJPEG";
        }
        if ($arr_image_details[2] == 3) {
            $imgt = "ImagePNG";
            $imgcreatefrom = "ImageCreateFromPNG";
        }
        if ($imgt) {
            $old_image = $imgcreatefrom("$updir" . DS . "$img");
            $new_image = imagecreatetruecolor($new_width, $new_height);
            imagecopyresized($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
            $create = $imgt($new_image, "$updir" . DS . "$thumb_beforeword" . DS . "$img");
            if ($create) {
                return true;
            }
        }
        return false;
    }

}
