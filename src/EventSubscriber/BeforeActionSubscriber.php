<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 *
 */
class BeforeActionSubscriber implements EventSubscriberInterface
{
    /**
     * @return string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'convertBodyDataFromJsonStringToAssociativeArray'
        ];
    }

    /**
     * @param ControllerEvent $event
     * @return void
     */
    public function convertBodyDataFromJsonStringToAssociativeArray(ControllerEvent $event)
    {
        $request = $event->getRequest();

        if ($request->getContentType() != 'json' || !$request->getContent() || is_array($request->getContent())) {
            return;
        }

        try {
            $data = json_decode($request->getContent(), true);
        } catch (\Exception $e) {
            echo $request->getContent();
            die;
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException('Invalid JSON Body: ' . json_last_error_msg());
        }

        $request->initialize(
            $request->query->all(),
            $request->request->all(),
            $request->attributes->all(),
            $request->cookies->all(),
            $request->files->all(),
            $request->server->all(),
            $data
        );
    }
}
