<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 11/10/2018
 * Time: 17:03
 */

namespace AppBundle\EventListener;


use AppBundle\Exception\OrderNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Routing\RouterInterface;

class ExceptionListener
{
    protected $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        
        $exception = $event->getException();

        if ($exception instanceof OrderNotFoundException)
        {

            $response = new RedirectResponse($this->router->generate('homepage'));
            $event->setResponse($response);

        }
    }
}