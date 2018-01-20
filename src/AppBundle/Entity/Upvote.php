<?php

namespace AppBundle\Entity;

use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 * Upvote
 *
 * @ORM\Table(name="upvote")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UpvoteRepository")
 */
class Upvote
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="upvotes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Topic", inversedBy="upvotes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $topic;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param user $user
     *
     * @return topic
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set topic
     *
     * @param topic $topic
     *
     * @return upvote
     */
    public function setTopic(Topic $topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic
     *
     * @return topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

}

