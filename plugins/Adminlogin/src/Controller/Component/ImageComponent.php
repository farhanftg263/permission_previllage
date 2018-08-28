<?php
namespace Adminlogin\Controller\Component;

require_once(ROOT . DS . 'vendor' . DS . 'Twilio' . DS . 'autoload.php');

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
/**
 * Common component
 */
class ImageComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];
    
    public function generatePasswordToken() {
        $output = array();
        // Generate a random string 100 chars in length.
        $token = "";
        for ($i = 0; $i < 100; $i++) {
            $d = rand(1, 100000) % 2;
            $d ? $token .= chr(rand(33,79)) : $token .= chr(rand(80,126));
        }
        (rand(1, 100000) % 2) ? $token = strrev($token) : $token = $token;
        // Generate hash of random string
        $hash = Security::hash($token, 'sha256', true);;
        for ($i = 0; $i < 20; $i++) {
            $hash = Security::hash($hash, 'sha256', true);
        }
        $output['reset_password_token'] = $hash;
        $output['token_created_at']     = date('Y-m-d H:i:s');
        return $output;
    }
    public function generateOTP($digits=4){
        $this->Users = TableRegistry::get('Users');
        $otp =  rand(pow(10, $digits-1), pow(10, $digits)-1);
        if($this->Users->exists(['Users.otp' => $otp])){
            $this->generateOTP($digits);
        }
        return $otp;
    }
    public function upload_appimage($base64_string, $dir, $thumbOptions = array()){        
        $f = finfo_open();
        $base64_string = str_replace(array('data:image/png;base64,', 'data:image/png;base64,','data:image/jpeg;base64,'), '', $base64_string);
        $base64_string = str_replace(' ', '+', $base64_string);
        $imgdata = base64_decode($base64_string);
        
        $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
        $ext = $this->getExtension($mime_type);
        //pr($ext); die;
        if(in_array($ext, array('jpg','jpeg','png','gif','pdf'))){
            $image = rand().time().'.'.$ext;
            $file = $dir.DS.$image;
            $success = file_put_contents($file, $imgdata);
            //fclose($f); 
            if($success){
                if(in_array('thumb', $thumbOptions)){
                    $this->makeThumbnails($dir, $image, 100, 100, 'thumb');
                }
                if(in_array('mobile', $thumbOptions)){
                    $this->makeThumbnails($dir, $image, 400, 400, 'mobile');
                }
                return $image;
            }
        }
        return false;
    }
    public function upload($file, $dir, $thumbOptions = array()){//pr($file);die;
        $image_name = $converted_name = $uploadFile = '';
        $image_name = $file['name'];
        $return = array();
        $flag = false;
        if (!empty($image_name)) {
            $extInfo = explode(".",$image_name);
            $mime = $this->fileMimeType($file['tmp_name']);
            $ext = $this->getExtension($mime);            
            //$ext = end($extInfo);
            $converted_name = time().rand().".".$ext;  
            $uploadFile = $dir.DS.$converted_name;
            //pr($uploadFile);die;
             if(in_array($ext, array('jpg','jpeg','gif'))){
               
                $size = $file['size'];
                //echo $size;die;
                if($size > (2*1024*1024)){
                    $quality = 100;
                }elseif($size > (1*1024*1024)){
                    $quality = 100;
                }else{
                    $quality = 100;
                }
                //$filename = $this->compress_image($file["tmp_name"], $uploadFile,$quality);
		        $filename =move_uploaded_file($file['tmp_name'],$uploadFile);
                if($filename){
                    $flag = true;
                }
            }elseif(move_uploaded_file($file['tmp_name'],$uploadFile)){
                
                $flag = true;
            }
            if($flag){
                if(in_array('mobile', $thumbOptions)){

                    $this->makeThumbnails($dir, $converted_name, 500, 395, 'mobile'); 
                }
                if(in_array('large', $thumbOptions)){

                    $this->makeThumbnails($dir, $converted_name, 1244, 400, 'large'); 
                }
                if(in_array('thumb', $thumbOptions)){
                    $this->makeThumbnails($dir, $converted_name, 400, 400, 'thumb');
                }
                
                return $converted_name;
            }
        }
        return null;
    }
    public function compress_image($source_url, $destination_url, $quality) {
        $info = getimagesize($source_url);
        if($info['mime'] == 'image/jpeg'){
            $image = imagecreatefromjpeg($source_url);
            imagejpeg($image, $destination_url, $quality);
        }elseif($info['mime'] == 'image/gif'){
            $image = imagecreatefromgif($source_url);
            imagegif($image, $destination_url, $quality);
        }elseif ($info['mime'] == 'image/png'){
            $image = imagecreatefrompng($source_url);   
            imagepng($image, $destination_url, $quality);
        }
        return $destination_url;
    }
    public function fileMimeType($source){
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $source);
        finfo_close($finfo);    
        return $mime;
    }
    public function getExtension ($key, $options = null){
        
        $types = array(
            'ai'      => 'application/postscript',
            'aif'     => 'audio/x-aiff',
            'aifc'    => 'audio/x-aiff',
            'aiff'    => 'audio/x-aiff',
            'asc'     => 'text/plain',
            'atom'    => 'application/atom+xml',
            'atom'    => 'application/atom+xml',
            'au'      => 'audio/basic',
            'avi'     => 'video/x-msvideo',
            'bcpio'   => 'application/x-bcpio',
            'bin'     => 'application/octet-stream',
            'bmp'     => 'image/bmp',
            'cdf'     => 'application/x-netcdf',
            'cgm'     => 'image/cgm',
            'class'   => 'application/octet-stream',
            'cpio'    => 'application/x-cpio',
            'cpt'     => 'application/mac-compactpro',
            'csh'     => 'application/x-csh',
            'css'     => 'text/css',
            'csv'     => 'text/csv',
            'dcr'     => 'application/x-director',
            'dir'     => 'application/x-director',
            'djv'     => 'image/vnd.djvu',
            'djvu'    => 'image/vnd.djvu',
            'dll'     => 'application/octet-stream',
            'dmg'     => 'application/octet-stream',
            'dms'     => 'application/octet-stream',
            'doc'     => 'application/msword',
            'docx'   => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'dtd'     => 'application/xml-dtd',
            'dvi'     => 'application/x-dvi',
            'dxr'     => 'application/x-director',
            'eps'     => 'application/postscript',
            'etx'     => 'text/x-setext',
            'exe'     => 'application/octet-stream',
            'ez'      => 'application/andrew-inset',
            'gif'     => 'image/gif',
            'gram'    => 'application/srgs',
            'grxml'   => 'application/srgs+xml',
            'gtar'    => 'application/x-gtar',
            'hdf'     => 'application/x-hdf',
            'hqx'     => 'application/mac-binhex40',
            'htm'     => 'text/html',
            'html'    => 'text/html',
            'ice'     => 'x-conference/x-cooltalk',
            'ico'     => 'image/x-icon',
            'ics'     => 'text/calendar',
            'ief'     => 'image/ief',
            'ifb'     => 'text/calendar',
            'iges'    => 'model/iges',
            'igs'     => 'model/iges',
            //'jpe'     => 'image/jpeg',
            'jpeg'    => 'image/jpeg',
            //'jpg'     => 'image/jpeg',
            'js'      => 'application/x-javascript',
            'json'    => 'application/json',
            'kar'     => 'audio/midi',
            'latex'   => 'application/x-latex',
            'lha'     => 'application/octet-stream',
            'lzh'     => 'application/octet-stream',
            'm3u'     => 'audio/x-mpegurl',
            'man'     => 'application/x-troff-man',
            'mathml'  => 'application/mathml+xml',
            'me'      => 'application/x-troff-me',
            'mesh'    => 'model/mesh',
            'mid'     => 'audio/midi',
            'midi'    => 'audio/midi',
            'mif'     => 'application/vnd.mif',
            'mov'     => 'video/quicktime',
            'movie'   => 'video/x-sgi-movie',
            'mp2'     => 'audio/mpeg',
            'mp3'     => 'audio/mpeg',
            'mpe'     => 'video/mpeg',
            'mpeg'    => 'video/mpeg',
            'mpg'     => 'video/mpeg',
            'mpga'    => 'audio/mpeg',
            'ms'      => 'application/x-troff-ms',
            'msh'     => 'model/mesh',
            'mxu'     => 'video/vnd.mpegurl',
            'nc'      => 'application/x-netcdf',
            'oda'     => 'application/oda',
            'ogg'     => 'application/ogg',
            'pbm'     => 'image/x-portable-bitmap',
            'pdb'     => 'chemical/x-pdb',
            'pdf'     => 'application/pdf',
            'pgm'     => 'image/x-portable-graymap',
            'pgn'     => 'application/x-chess-pgn',
            'png'     => 'image/png',
            'pnm'     => 'image/x-portable-anymap',
            'ppm'     => 'image/x-portable-pixmap',
            'ppt'     => 'application/vnd.ms-powerpoint',
            'ps'      => 'application/postscript',
            'qt'      => 'video/quicktime',
            'ra'      => 'audio/x-pn-realaudio',
            'ram'     => 'audio/x-pn-realaudio',
            'ras'     => 'image/x-cmu-raster',
            'rdf'     => 'application/rdf+xml',
            'rgb'     => 'image/x-rgb',
            'rm'      => 'application/vnd.rn-realmedia',
            'roff'    => 'application/x-troff',
            'rss'     => 'application/rss+xml',
            'rtf'     => 'text/rtf',
            'rtx'     => 'text/richtext',
            'sgm'     => 'text/sgml',
            'sgml'    => 'text/sgml',
            'sh'      => 'application/x-sh',
            'shar'    => 'application/x-shar',
            'silo'    => 'model/mesh',
            'sit'     => 'application/x-stuffit',
            'skd'     => 'application/x-koan',
            'skm'     => 'application/x-koan',
            'skp'     => 'application/x-koan',
            'skt'     => 'application/x-koan',
            'smi'     => 'application/smil',
            'smil'    => 'application/smil',
            'snd'     => 'audio/basic',
            'so'      => 'application/octet-stream',
            'spl'     => 'application/x-futuresplash',
            'src'     => 'application/x-wais-source',
            'sv4cpio' => 'application/x-sv4cpio',
            'sv4crc'  => 'application/x-sv4crc',
            'svg'     => 'image/svg+xml',
            'svgz'    => 'image/svg+xml',
            'swf'     => 'application/x-shockwave-flash',
            't'       => 'application/x-troff',
            'tar'     => 'application/x-tar',
            'tcl'     => 'application/x-tcl',
            'tex'     => 'application/x-tex',
            'texi'    => 'application/x-texinfo',
            'texinfo' => 'application/x-texinfo',
            'tif'     => 'image/tiff',
            'tiff'    => 'image/tiff',
            'tr'      => 'application/x-troff',
            'tsv'     => 'text/tab-separated-values',
            'txt'     => 'text/plain',
            'ustar'   => 'application/x-ustar',
            'vcd'     => 'application/x-cdlink',
            'vrml'    => 'model/vrml',
            'vxml'    => 'application/voicexml+xml',
            'wav'     => 'audio/x-wav',
            'wbmp'    => 'image/vnd.wap.wbmp',
            'wbxml'   => 'application/vnd.wap.wbxml',
            'wml'     => 'text/vnd.wap.wml',
            'wmlc'    => 'application/vnd.wap.wmlc',
            'wmls'    => 'text/vnd.wap.wmlscript',
            'wmlsc'   => 'application/vnd.wap.wmlscriptc',
            'wrl'     => 'model/vrml',
            'xbm'     => 'image/x-xbitmap',
            'xht'     => 'application/xhtml+xml',
            'xhtml'   => 'application/xhtml+xml',
            'xls'     => 'application/vnd.ms-excel',
            'xml'     => 'application/xml',
            'xpm'     => 'image/x-xpixmap',
            'xsl'     => 'application/xml',
            'xslt'    => 'application/xslt+xml',
            'xul'     => 'application/vnd.mozilla.xul+xml',
            'xwd'     => 'image/x-xwindowdump',
            'xyz'     => 'chemical/x-xyz',
            'zip'     => 'application/zip',
            'mp4' => 'video/mp4'
          );
        if($options == 'mime_type'){
            return $types[$key];
        }else{
            $flipped = array_flip($types);
            return $flipped[$key];
        }    
    }
    public function makeThumbnails($updir, $img, $thumbnail_width = 150, $thumbnail_height = 150, $thumb_beforeword = "thumb"){

        $arr_image_details = getimagesize("$updir" .DS. "$img"); // pass id to thumb name
        $original_width = $arr_image_details[0];
        $original_height = $arr_image_details[1];
       

	if($original_width > $thumbnail_width || $original_height > $thumbnail_height)
	{
		if ($original_width  > $original_height) {
		    $new_width = $thumbnail_width;
		    $new_height = ($original_height * $new_width) / $original_width;
		}
		else if($original_width < $original_height) {
		    $new_height = $thumbnail_height;
		    $new_width = ($original_width * $new_height) / $original_height;
		}
		else
		{
		    $new_width= $new_height = $thumbnail_width;
		}
	}
	else
	{
		$new_width=$original_width;
		$new_height=$original_height;
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
            $old_image = $imgcreatefrom("$updir" .DS. "$img");
            $new_image = imagecreatetruecolor($new_width, $new_height);
            imagecopyresized($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
            $create = $imgt($new_image, "$updir" .DS. "$thumb_beforeword" .DS. "$img");
            if($create){
                return true;
            }
        }
        return false;
    }
    public function loadLangFile($file=array()){      
        //$lang = $this->Session->read('language');
        if(empty($lang))
            $lang = 'en';
        if(count($file)>0){
            foreach($file as $val){
               Configure::load("language".DS.'en'.DS.$val);
            }
        }
    }
    public function randomPassword()
    {
        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($data), 0, 7);
    }
    public function upload_app_video($base64_string, $dir, $thumbOptions = array()){        
        $f = finfo_open();
        $base64_string = str_replace(array('data:video/mp4;base64,', 'data:image/png;base64,'), '', $base64_string);
        $base64_string = str_replace(' ', '+', $base64_string);
        $imgdata = base64_decode($base64_string);
        $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
        $ext = $this->getExtension($mime_type);
        if(in_array($ext, array('mp4'))){
            $video = rand().time().'.'.$ext;
            $file = $dir.DS.$video;
            $success = file_put_contents($file, $imgdata);
            //fclose($f); 
            if($success){
                return $video;
            }
        }
        return false;
    }
    public function upload_video($dir,$file,&$converted_name)
    {
        $video_name = $converted_name = $uploadFile = '';
        $video_name = $file['name'];
        $return = array();
        $flag = false; 
        if (!empty($video_name)) 
        {
            $mime = $this->fileMimeType($file['tmp_name']);
            $ext = $this->getExtension($mime);            
            $converted_name = time().rand().".".$ext;  
            $uploadFile = $dir.DS.$converted_name;
            if(in_array($ext, array('mp4')))
            {
		$filename = move_uploaded_file($file['tmp_name'],$uploadFile);
                if($filename){
                   return true;
                }
            }
        }
        return false;
    }
    /*
     * Upload video with thumbnail
     */
    public function upload_video_with_thumb($dir = array(),$file,&$return_param)
    {
        $video_name = $converted_name = $uploadFile = '';
        $video_name = $file['name'];
        $return = array();
        $flag = false; 
        $return_param = [
            'error' => 1,
            'video_name' => $video_name,
            'msg' => ''
        ];
        if (!empty($video_name)) 
        {
            $mime = $this->fileMimeType($file['tmp_name']);
            $ext = $this->getExtension($mime);      
            $random_num = time().rand();
            $converted_name = $random_num.".".$ext;  
            $uploadFile = $dir['video'].DS.$converted_name;
            $thumbnail = 'my_thumbnail.png';
            if(in_array($ext, array('mp4')))
            {
		$filename = move_uploaded_file($file['tmp_name'],$uploadFile);
                if($filename)
                {
                    //created thubnail
                    $upload_thumb = $dir['thumb'].DS.$random_num.".jpg";
                    
                    $time = 3;
                    $infile = $uploadFile;
                    $thumbnail = $upload_thumb;
                    $ffprobe = FFMpeg\FFProbe::create();
                    
                    // get video dimension
                    $dimension = $ffprobe
                        ->streams($uploadFile) // extracts streams informations
                        ->videos()            // filters video streams
                        ->first()            // returns the first video stream
                        ->getDimensions();   
                    $width = $dimension->getWidth();
                    $height = $dimension->getHeight();
                    
                    $size = "{$width}x{$height}";
                    ///////
                    
                    $cmd = sprintf(
                        'ffmpeg -i %s -ss %s -f image2 -vframes 1 %s -s %s %s 2>&1',
                        $infile, $time, $thumbnail,$size,$thumbnail
                    ); 
                    exec($cmd);
                    
                    $return_param = [
                        'error' => 0,
                        'video_name' => $converted_name,
                        'msg' => 'Video upload successfully'
                    ];
                }
                else
                {
                    $return_param = [
                        'error' => 1,
                        'video_name' => '',
                        'msg' => 'Video could not uploaded'
                    ];
                }
            }
            else
            {
                $return_param = [
                    'error' => 1,
                    'video_name' => '',
                    'msg' => 'Only MP4 is allowed to upload'
                ];
            }
        }
        return $return_param;
    }
    /*
     * Calculate disk size
     */
    public function CalcDiskSize($DirectoryPath) 
    {
        $Size = 0;
        $Dir = opendir($DirectoryPath);

        if (!$Dir)
            return -1;

        while (($File = readdir($Dir)) !== false)
        {
            // Skip file pointers
            if ($File[0] == '.') continue; 

            // Go recursive down, or add the file size
            if (is_dir($DirectoryPath . $File))            
                $Size += $this->CalcDiskSize($DirectoryPath . $File . DIRECTORY_SEPARATOR);
            else 
                $Size += filesize($DirectoryPath . $File);        
        }
        closedir($Dir);
        return $Size;
    }
    public function get_year_weeks(&$year_weeks,&$year_weeks_month,$year = 2017)
    {
        $weeks = $this->getIsoWeeksInYear($year);
        for($x=1; $x<=$weeks; $x++)
        {
            $dates = $this->getStartAndEndDate($x, $year);
            $month = date('F',strtotime($dates['week_start']));
            $year_weeks[$x][0] = $dates['week_start'];
            $year_weeks[$x][1] = $dates['week_end'];
            $year_weeks_month[$month][] = $x;
        }

    }
    private function getIsoWeeksInYear($year) 
    {
        $date = new \DateTime;
        $date->setISODate($year, 53);
        return ($date->format("W") === "53" ? 53 : 52);
    }

    private function getStartAndEndDate($week, $year) 
    {
        $dto = new \DateTime();
        $ret['week_start'] = $dto->setISODate($year, $week)->format('Y-m-d');
        $ret['week_end'] = $dto->modify('+6 days')->format('Y-m-d');
        return $ret;
    }
    /*
     * Calculate distance in miles 
     */
    public function distance($lat1,$lng1,$lat2,$lng2,$unit)
    {
        $theta = $lng1 - $lng2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "M") {
            return (($miles * 1.609344)/1000);
          } 
    }
    /*
     * Get Client ip address
     */
    function get_client_ip()
    {
         $ipaddress = '';
         if (getenv('HTTP_CLIENT_IP'))
             $ipaddress = getenv('HTTP_CLIENT_IP');
         else if(getenv('HTTP_X_FORWARDED_FOR'))
             $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
         else if(getenv('HTTP_X_FORWARDED'))
             $ipaddress = getenv('HTTP_X_FORWARDED');
         else if(getenv('HTTP_FORWARDED_FOR'))
             $ipaddress = getenv('HTTP_FORWARDED_FOR');
         else if(getenv('HTTP_FORWARDED'))
             $ipaddress = getenv('HTTP_FORWARDED');
         else if(getenv('REMOTE_ADDR'))
             $ipaddress = getenv('REMOTE_ADDR');
         else
             $ipaddress = 'UNKNOWN';

         return $ipaddress;
    }
}