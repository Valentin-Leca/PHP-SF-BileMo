<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface {

    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function onKernelException(ExceptionEvent $event): void {

        $exception = $event->getThrowable();

        $id = ctype_digit($this->requestStack->getCurrentRequest()->get('id'));

        if ($exception->getStatusCode() === 404 && $id !== true) {
            $data = [
                'status' => 400,
                'message' => "Le paramètre 'id' n'accepte que les chiffres."
            ];
            $response = new JsonResponse($data , 400);
            $event->allowCustomResponseCode();
            $event->setResponse($response);
        } else {
            if ($exception instanceof HttpException) {
                $data = [
                    'status' => $exception->getStatusCode(),
                    'message' => $exception->getMessage()
                ];
            } else {
                $data = [
                    'status' => 500,
                    'message' => $exception->getMessage()
                ];
            }
            $event->setResponse(new JsonResponse($data));
        }
    }

    public static function getSubscribedEvents(): array {

        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
