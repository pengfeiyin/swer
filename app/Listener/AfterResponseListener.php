<?php
/**
 * Created by PhpStorm.
 * User: yinpengfei
 * Date: 2017/9/27
 * Time: 上午11:23
 */

namespace App\Listener;


use App\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AfterResponseListener implements EventSubscriberInterface
{
    public function onResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $response->setContent($response->getContent() . 'After');
    }

    public static function getSubscribedEvents()
    {
        return ['response' => 'onResponse'];
    }
}