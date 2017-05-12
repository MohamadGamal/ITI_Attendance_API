<?php

namespace AppBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;        
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


/**
     * 
     * @ORM\ManyToOne(targetEntity="Track", inversedBy="users")
     * @ORM\JoinColumn(name="track_id", referencedColumnName="id")
     */
    private $track;


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
     * Set track
     *
     * @param \AppBundle\Entity\Track $track
     *
     * @return User
     */
    public function setTrack(\AppBundle\Entity\Track $track = null)
    {
        $this->track = $track;

        return $this;
    }

    /**
     * Get track
     *
     * @return \AppBundle\Entity\Track
     */
    public function getTrack()
    {
        return $this->track;
    }
}