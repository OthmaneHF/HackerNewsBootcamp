<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\RouterInterface;

class ExceptionListener
{

    private $router;

    private $session;

    public function __construct(RouterInterface $router, Session $session)
    {
        $this->router  = $router;
        $this->session = $session;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // Get the exception object from the event
        $exception = $event->getException();
        $message = sprintf(
            'ERROR MESSAGE : %s with code: %s',
            $exception->getMessage(),
            $exception->getCode()
        );

        $this->session->getFlashBag()->add('danger', $exception->getMessage());

        // Simple Response object
        $response = new Response();
        $response->setContent($message);


        // Send the modified response object            
        $event->setResponse(new RedirectResponse($this->router->generate('homepage')));    

    }
}