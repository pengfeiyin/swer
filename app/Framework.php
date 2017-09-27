<?php
/**
 * Created by PhpStorm.
 * User: yinpengfei
 * Date: 2017/9/26
 * Time: 下午10:43
 */

namespace App;


use App\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response as Response;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class Framework implements HttpKernelInterface
{
    private $dispatcher;
    private $matcher;
    private $controllerResolver;
    private $argumentResolver;

    public function __construct(EventDispatcher $dispatcher, UrlMatcherInterface $matcher, ControllerResolverInterface $controllerResolver, ArgumentResolverInterface $argumentResolver)
    {
        $this->dispatcher = $dispatcher;
        $this->matcher = $matcher;
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver = $argumentResolver;
    }

    public function handler(Request $request)
    {
        $this->matcher->getContext()->fromRequest($request);
        try {
            $request->attributes->add($this->matcher->match($request->getPathInfo()));
            $controller = $this->controllerResolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);
            $response = call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $e) {
            $response = new Response('Not Found', 404);
        } catch (\Exception $e) {
            $response = new Response('Error Occurred', 500);
        }
        $this->dispatcher->dispatch('response', new ResponseEvent($response, $request));
        return $response;
    }


    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $this->matcher->getContext()->fromRequest($request);
        try {
            $request->attributes->add($this->matcher->match($request->getPathInfo()));
            $controller = $this->controllerResolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);
            $response = call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $e) {
            $response = new Response('Not Found', 404);
        } catch (\Exception $e) {
            $response = new Response('Error Occurred', 500);
        }
        $this->dispatcher->dispatch('response', new ResponseEvent($response, $request));
        //设置cache ttl
        $response->setTtl(10);
        return $response;
    }
}