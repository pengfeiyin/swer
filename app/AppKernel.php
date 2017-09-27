<?php
/**
 * Created by PhpStorm.
 * User: yinpengfei
 * Date: 2017/9/21
 * Time: 上午8:40
 */

namespace App;


use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait as MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class AppKernel extends HttpKernel
{
    use MicroKernelTrait;

    private $loader = null;

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        // TODO: Implement configureContainer() method.
        $this->loader = $loader;
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        // TODO: Implement configureRoutes() method.
    }


}