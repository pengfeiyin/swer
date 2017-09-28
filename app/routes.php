<?php
/**
 * Created by PhpStorm.
 * User: yinpengfei
 * Date: 2017/9/26
 * Time: 下午10:56
 */
use Symfony\Component\Routing\RouteCollection as RouteCollection;
use Symfony\Component\Routing\Route as Route;

$routes = new RouteCollection();
$route = new Route('/swer/test2/{name}', ['name' => '1', '_controller' => 'App\Controller\BlogController::listAction']);
$route->setMethods('POST');
$routes->add('test2', $route);


return $routes;
