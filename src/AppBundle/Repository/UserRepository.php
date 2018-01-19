<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * UserRepository
 *
 */
class UserRepository extends EntityRepository implements UserProviderInterface,OAuthAwareUserProviderInterface
{

	public function loadUserByUsername($email) {
     return $this->getEntityManager()
         ->createQuery('SELECT u FROM
         AppBundle:User u
         WHERE u.email = :email')
         ->setParameters(['email' => $email])
         ->getOneOrNullResult();
    }
 
    public function refreshUser(UserInterface $user) {
        return $this->loadUserByUsername($user->getUsername());
    }
 
    public function supportsClass($class) {
        return $class === 'AppBundle\Entity\User';
    }
 
 public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        // Get Facebook Id & Email
        $facebook_id = $response->getUsername(); 
        $facebook_email = $response->getEmail();

        $user = $this->findOneBy(array('facebook_id' => $facebook_id));

        if (null === $user) { 

            $user = new User();
            $user->setEmail($facebook_email);
            $user->setFacebookId($facebook_id);

            $em = $this->getEntityManager();
            $em->persist($user);
            $em->flush();
        }


        return $user;
    }
}
