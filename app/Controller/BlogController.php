<?php
/**
 * Created by PhpStorm.
 * User: yinpengfei
 * Date: 2017/9/23
 * Time: 下午10:33
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    public function listAction(Request $request): Response
    {
//        return $this->json(['tou' => $request->get('name'), 'path' => $request->getPathInfo()], 200, ['try' => '1']);
        return new JsonResponse(['name' => $request->get('name'), 'path' => $request->getPathInfo()], 200, ['kk' => 'vv']);

    }

    public function showAction($slug)
    {
        var_dump("blog show action : $slug");
    }
}