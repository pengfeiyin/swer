<?php
/**
 * Created by PhpStorm.
 * User: yinpengfei
 * Date: 2017/9/27
 * Time: 上午10:48
 */

namespace App\Event;


use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseEvent extends Event
{
    private $request;
    private $response;

    public function __construct(Response $response, Request $request)
    {
        $this->response = $response;
        $this->request = $request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getRequest()
    {
        return $this->request;
    }
}