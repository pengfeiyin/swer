<?php
/**
 * Created by PhpStorm.
 * User: yinpengfei
 * Date: 2017/9/21
 * Time: 上午8:40
 */

namespace App;


use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel as Kernel;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollectionBuilder;

class AppKernel extends Kernel
{
    use MicroKernelTrait;

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        // TODO: Implement configureRoutes() method.

//        add('swer', new Route('/swer/{name}', [
//            '_controller' => function(Request $request) {
//                return new Response(sprintf("Hello %s", $request->get('name')));
//            }
//        ]));
        $routes->addRoute(new Route('/swer/{name}', [
            '_controller' => function(Request $request) {
                return new Response(sprintf("Hello cc %s", $request->get('name')));
            }
        ]), 'swer');
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        // TODO: Implement configureContainer() method.
    }


    public function registerBundles()
    {
        // TODO: Implement registerBundles() method.
        return array(
            new FrameworkBundle()
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        // TODO: Implement registerContainerConfiguration() method.
    }

    public function getRootDir()
    {
        return __DIR__;
    }


}