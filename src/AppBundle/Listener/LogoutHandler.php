<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

class LogoutHandler implements LogoutHandlerInterface {
    private $session;

    public function __construct(SessionInterface $session){
        $this->session = $session;
    }
    
    public function logout(Request $Request, Response $Response, TokenInterface $Token) {

        $this->session->getFlashBag()->add('success', 'LOGGED OUT!');

       
    }
}