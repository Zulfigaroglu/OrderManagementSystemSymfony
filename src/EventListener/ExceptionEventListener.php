<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionEventListener
{
    /**
     * @throws \Throwable
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $code = $exception->getCode();
        if($code == 0){
            throw $exception;
        }

        $message = $exception->getMessage();
        $data = ["message" => $message];
        if($this->isJson($message))
        {
            $data = json_decode($message);
        }

        $response = new JsonResponse($data, $code);
        $event->setResponse($response);
    }

    function isJson($string) {

        return !is_null(json_decode($string));
    }
}