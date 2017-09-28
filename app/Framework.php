<?php
/**
 * Created by PhpStorm.
 * User: yinpengfei
 * Date: 2017/9/26
 * Time: 下午10:43
 */

namespace App;


use App\Event\ResponseEvent;
use App\Listener\AfterResponseListener;
use App\Listener\BeforeResponseListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response as Response;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver as ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver as ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\ResponseListener as ResponseListener;
use Symfony\Component\HttpKernel\EventListener\RouterListener as RouterListener;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Framework extends HttpKernel
{
    public function __construct(RouteCollection $routes)
    {
        $context = new RequestContext();
        $matcher = new UrlMatcher($routes, $context);
        $requestStack = new RequestStack();

        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new RouterListener($matcher, $requestStack));
        $dispatcher->addSubscriber(new ResponseListener('UTF-8'));

        $dispatcher->addSubscriber(new BeforeResponseListener());
        $dispatcher->addSubscriber(new AfterResponseListener());

        parent::__construct($dispatcher, $controllerResolver, $requestStack, $argumentResolver);
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        try {
            $response = parent::handle($request, $type, $catch);
        } catch (NotFoundHttpException $e) {
            $response = new Response('Not Found', Response::HTTP_NOT_FOUND);
        } catch (MethodNotAllowedHttpException $e) {
            $response = new Response('Method Not Allowed', Response::HTTP_METHOD_NOT_ALLOWED);
        } catch (\Exception $e) {
            $response = new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        $this->dispatcher->dispatch('response', new ResponseEvent($response, $request));
        return $response;
    }
}