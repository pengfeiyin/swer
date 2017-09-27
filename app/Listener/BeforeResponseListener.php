<?php
/**
 * Created by PhpStorm.
 * User: yinpengfei
 * Date: 2017/9/27
 * Time: 上午11:17
 */

namespace App\Listener;


use App\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BeforeResponseListener implements EventSubscriberInterface
{
    public function onResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $response->setContent('before' . $response->getContent());
    }

    public static function getSubscribedEvents()
    {
        return ['response' => 'onResponse'];
    }
}