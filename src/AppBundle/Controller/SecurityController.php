<?php

namespace AppBundle\Controller;


use AppBundle\Form\LoginFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends Controller
{
    
    /**
     * @Route("/login", name="security_login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        
        $error = $authUtils->getLastAuthenticationError();
        
        $last_username = $authUtils->getLastUsername();

        $form = $this->createForm(LoginFormType::class,[
            'email' => $last_username
        ]);


        return $this->render('security/login.html.twig', [
            'loginForm' => $form->createView(),
            'error'=> $error

        ]);
    }
    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        
        throw new \Exception('This should not be reached');
    }
}
