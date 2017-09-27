<?php
/**
 * Created by PhpStorm.
 * User: yinpengfei
 * Date: 2017/9/18
 * Time: 下午6:56
 */

namespace App;

use App\Event\ResponseEvent;
use App\Listener\AfterResponseListener;
use App\Listener\BeforeResponseListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class App
{
    private $sRequest;
    private $sResponse;
    private $request;
    private $response;

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
        unset($this->request);
        unset($this->response);
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
        $this->request = new Request($this->getGET(),
                                    $this->getPOST(),
                                    [],
                                    $this->getCOOKIE(),
                                    $this->getFILES(),
                                    $this->getSERVER(),
                                    $this->getRAW());
        $this->request->headers = new HeaderBag($this->getHEADER());

        // route configuration
        $routes = require __DIR__ . "/routes.php";
        $context = new RequestContext();
        $context->fromRequest($this->request);
        $matcher = new UrlMatcher($routes, $context);
        $requestStack = new RequestStack();

        // event dispatcher configuration
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new BeforeResponseListener());
        $dispatcher->addSubscriber(new AfterResponseListener());
        $dispatcher->addSubscriber(new RouterListener($matcher, $requestStack));
//        $dispatcher->addListener('response', [new BeforeResponseListener(), 'onResponse']);
//        $dispatcher->addListener('response', [new AfterResponseListener(), 'onResponse']);

        // resolver configuration
        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();

        $framework = new Framework($dispatcher, $matcher, $controllerResolver, $argumentResolver);
        $framework = new HttpCache(
            $framework,
            new Store(__DIR__ . '/../cache')
        );
        $response = $framework->handle($this->request);
        $response->send();
    }
}