<?php
use Cake\Core\Configure;
use Cake\Core\Plugin;
$baseUrl = '';

$baseUrl = 'http://demo.newmediaguru.co/migrateoutfitterdev';

Configure::write('Adminlogin.App', [
        'title' => 'migrateoutfitters',
        'pageLimit' => 10,
        'remembermeExpires' => '15 Minute',
        'baseUrl' => $baseUrl,
        'fileUrl' => $baseUrl.'/adminlogin',
        'filePath' => Plugin::path('Adminlogin').'webroot',
        'adminPath' => Plugin::path('Adminlogin'),
    ]
);
 Configure::write('Site', [
        'title' => 'Migrate Outfitters',
        'logo' => [
            'mini' => '<b>Migrate Outfitters</b>',
            'large' => '<b>Migrate Outfitters</b>'
        ],
        //'baseUrl' => 'http://localhost/migrateoutfitters',
        'baseUrl' => $baseUrl,
    ]);


 require_once 'constant.php';
date_default_timezone_set('America/Chicago');
