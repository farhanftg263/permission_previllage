<?php
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
//common constant
define('USER_ACTIVE',1);
define('USER_INACTIVE',0);


// Push Notification
define('IOS_APP_KEY','AAAAKZLje1I:APbGQDw8FD...TjmtuINVB-g');
define('ANDROID_APP_KEY','AAAAhbM-OT0:APA91bEn-KL_weyDnlF2hosIY35ue2KZjb2lEiTXTgs9ycmdA-wmuE7gbW-9QySnvtdVY67T8272bbZUZenaiPEJTHhJXaiuiL5oki65ZGzUGLnock16B2zoVs50sEKv6M5ZviZgx4tt');

//Roles
define('ADMIN',1);
define('SUPERADMIN',9);
define('SUB_ADMIN',4);
define('ADMIN_USERS',8);
//Path
define('FRONTEND_IMG_PATH','/frontend/images/');
define('UPLOAD_PATH',ROOT.DS.'webroot'.DS.'uploads'.DS);

define('WEATHER',1);