<?php
/**
 * Created by PhpStorm.
 * User: yinpengfei
 * Date: 2017/9/18
 * Time: 下午6:56
 */

namespace App;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\HttpCache\HttpCache as HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store as Store;

class App
{
    private $sRequest;
    private $sResponse;

    public function __construct(\swoole_http_request $request, \swoole_http_response $response)
    {
        $this->sRequest = $request;
        $this->sResponse = $response;

        $this->wrapRequest();
    }

    function __destruct()
    {
        // TODO: Implement __destruct() method.
        unset($this->sRequest);
        unset($this->sResponse);
    }

    public function send()
    {

    }

    private function getGET()
    {
        return isset($this->sRequest->get) ? $this->sRequest->get : [];
    }

    private function getPOST()
    {
        return isset($this->sRequest->post) ? $this->sRequest->post : [];
    }

    private function getRAW()
    {
        return $this->sRequest->rawContent();
    }

    private function getCOOKIE()
    {
        return isset($this->sRequest->cookie) ? $this->sRequest->cookie : [];
    }

    private function getSERVER()
    {
        $server = [];
        foreach ($this->sRequest->server as $key => $value) {
            $key = strtoupper($key);
            $server[$key] = $value;
        }
        return $server;
    }

    private function getHEADER()
    {
        return isset($this->sRequest->header) ? $this->sRequest->header : [];
    }

    private function getFILES()
    {
        return isset($this->sRequest->files) ? $this->sRequest->files : [];
    }

    private function wrapRequest()
    {
        $request = new Request($this->getGET(),
                                    $this->getPOST(),
                                    [],
                                    $this->getCOOKIE(),
                                    $this->getFILES(),
                                    $this->getSERVER(),
                                    $this->getRAW());
        $request->headers = new HeaderBag($this->getHEADER());
        
        $routes = require __DIR__ . '/routes.php';
        $framework = new Framework($routes);
        $framework = new HttpCache($framework, new Store(__DIR__ . '/../cache'));
        $framework->handle($request)->send();
    }
}