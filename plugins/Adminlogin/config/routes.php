<?php

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/* Router::plugin(
  'Adminlogin',
  ['path' => '/adminlogin'],
  function (RouteBuilder $routes) {
  $routes->fallbacks(DashedRoute::class);
  }
  ); */
Router::prefix('securehost', function ($routes) {
    $routes->plugin(
            'Adminlogin', ['path' => '/adminlogin'], function (RouteBuilder $routes) {
        $routes->fallbacks(DashedRoute::class);
    }
    );
});
