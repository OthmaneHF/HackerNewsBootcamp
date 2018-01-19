<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
	private $router;
	private $session;

	public function __construct(RouterInterface $router,SessionInterface $session)
	{
		$this->router = $router;
		$this->session = $session;
	}

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {

    	if($request->getPathInfo() == '/login' || $request->getPathInfo() == '/register')
    	{

        	$this->session->getFlashBag()->add('warning', 'You are already logged in!');

        	return new RedirectResponse($this->router->generate('homepage'));
    	}
    }
}