<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields={"email"},message="This email is already registered in our database")
 */
class User implements UserInterface
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
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", nullable=true)
     */
    private $facebook_id;


    /**
     * @Assert\NotBlank(groups={"Registration"})
     * @Assert\Length(max=4096)
     */
    private $plainPassword;


    /**
     *
     * @ORM\OneToMany(targetEntity="Topic", mappedBy="user")
     */
    private $topics;


    /**
     *
     * @ORM\OneToMany(targetEntity="Upvote", mappedBy="user")
     */
    private $upvotes;


    public function __construct()
    {
        $this->topics = new ArrayCollection();
    }



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
     * Get Username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Set Username
     *
     * @param string $email
     *
     * @return User
     */
    public function setUsername($email)
    {
        $this->email = $email;

        return $this;
    }


    /**
     * Set FacebookId
     *
     * @param string $facebook_id
     *
     * @return User
     */
    public function setFacebookId($facebook_id)
    {
        $this->facebook_id = $facebook_id;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * Get plain password
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set plain password
     *
     * @return string
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Erase Credentials
     * Called post-login
     */
    public function eraseCredentials()
    {
        // to avoid plainpassword getting saved anywhere
        $this->plainPassword = null;
    }

    /**
     * Get Roles
     * @return [string]
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }


    /**
     * Get Topics
     * @return [topic]
     */
    public function getTopics()
    {
        return $this->topics;
    }


    /**
     * Get Salt
     */
    public function getSalt()
    {
    }

}

