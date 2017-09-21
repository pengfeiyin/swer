<?php
/**
 * Created by PhpStorm.
 * User: yinpengfei
 * Date: 2017/9/18
 * Time: 下午4:57
 */
use App\App as App;

require __DIR__ . '/../vendor/autoload.php';

$http = new swoole_http_server('127.0.0.1', 9503);
$serverConfig = require __DIR__ . '/../config/server.php';
$http->set($serverConfig);

//$http->on('WorkerStart', function(swoole_http_server $server, int $workerId) {
//
//});
//
//$http->on('WorkerStop', function(swoole_http_server $server, int $workerId) {
//
//});
//
//$http->on('Start', function(swoole_http_server $server) {
//
//});

$http->on('request', function(swoole_http_request $request, swoole_http_response $response) {
    ob_start();
    $app = new App($request, $response);
    $app->send();
    $result = ob_get_contents();
    ob_end_clean();
    $response->end($result);
    unset($app);
});

$http->start();