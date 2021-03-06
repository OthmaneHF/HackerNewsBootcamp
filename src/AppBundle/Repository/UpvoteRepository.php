<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Upvote;
use AppBundle\Exception\UpvoteExistsException;

/**
 * UpvoteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UpvoteRepository extends \Doctrine\ORM\EntityRepository
{

    public function AddIfNotExists($topic,$user)
    {

        $existingUpvote =  $this->findOneBy(
                array('user' => $user->getId(), 'topic' => $topic->getId())
            );

        if($existingUpvote)
        {
            throw new UpvoteExistsException("You have already upvoted this topic.");
        }

        $newUpvote = new Upvote;
        $newUpvote->setUser($user);
        $newUpvote->setTopic($topic);

        $em = $this->getEntityManager();
        $em->persist($newUpvote);
        $em->flush();

    }

}
