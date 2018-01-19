<?php

namespace AppBundle\Security;

use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use AppBundle\Form\LoginFormType;


class LoginFormAuthenticator extends AbstractGuardAuthenticator
{
    
    private $passwordEncoder;

    private $logger;

    private $router;

    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory, LoggerInterface $logger,UserPasswordEncoderInterface $passwordEncoder, RouterInterface $router)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->logger = $logger;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning false will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request)
    {
        return ($request->getPathInfo() == '/login' && $request->isMethod('POST'));
    }


    public function getCredentials(Request $request)
    {   
        $form = $this->formFactory->create(LoginFormType::class);
        $form->handleRequest($request);

        $data = $form->getData();

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $data['email']
        );

        $test = $request->getSession()->get(Security::LAST_USERNAME);
        $this->logger->info('email : '.$test);

        return $data;
    }


    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $email = $credentials['email'];
        $this->logger->info('email getUser : '.$email);

        if (null === $email) {
            return;
        }
        
        return $userProvider->loadUserByUsername($email);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {   

        $plainPassword = $credentials['password'];
        $encoder = $this->passwordEncoder;

        if(!$encoder->isPasswordValid($user, $plainPassword) OR $plainPassword == null OR !$plainPassword) {
            return null;
        }

        // return true to cause authentication success
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        $url = $this->router->generate('homepage');
        return new RedirectResponse($url);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        $request->getSession()->set(Security::LAST_USERNAME, $request->get('email'));

        $response = new RedirectResponse($this->router->generate('security_login'));
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        
        return new RedirectResponse($this->router->generate('security_login'));
    }

    public function supportsRememberMe()
    {
        return false;
    }


}
