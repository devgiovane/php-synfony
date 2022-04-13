<?php


namespace App\Event;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;


class OptionsEvent implements EventSubscriberInterface
{
    /**
     * @param ControllerEvent $event
     */
    public function onKernelController(ControllerEvent $event)
    {
        $request = $event->getRequest();
        if($request->getMethod() == "OPTIONS") {
            $event->setController(function () {
                $response = new Response();
                $response->setStatusCode(Response::HTTP_NO_CONTENT);
                return $response;
            });
        }
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController'
        ];
    }
}
