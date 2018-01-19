<?php

namespace AppBundle\Repository;

/**
 * TopicRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TopicRepository extends \Doctrine\ORM\EntityRepository
{

    public function findByCreatedAtOrder($order)
    {
        return $this->findBy(array(), array('createdAt' => $order));
    }

    public function getTopicsOrderedByNumberOfUpvotes()
	{
	    return $this->createQueryBuilder('topic')
	        ->addSelect('COUNT(upvotes) AS HIDDEN upvoteCount')
	        ->leftJoin('topic.upvotes', 'upvotes')
	        ->groupBy('topic')
	        ->orderBy('upvoteCount', 'DESC')
    		->getQuery()->getResult();

	}
}
