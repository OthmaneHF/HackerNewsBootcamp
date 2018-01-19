<?php

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\RegisterFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    
    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {


        $user = new User();

        $RegisterForm = $this->createForm(RegisterFormType::class);

        // handle the submit (will only happen on POST)
        $RegisterForm->handleRequest($request);

        if ($RegisterForm->isSubmitted() && $RegisterForm->isValid()) {

            $user = $RegisterForm->getData();

            // Encode password
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // save User
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                        $user, 
                        $request,
                        $this->get('AppBundle\Security\LoginFormAuthenticator'), // Authenticator who's success behavior is mimiced
                        'main' // Name of the firewall handling this
                    );

            return new Response('Successfully Signed up!');
        }

        
        // replace this example code with whatever you need
        return $this->render('user/register.html.twig', [
            'RegisterForm' => $RegisterForm->createView(),
        ]);
    }
}
