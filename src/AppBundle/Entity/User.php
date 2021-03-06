<?php

namespace AppBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;        
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * 
     * @ORM\OneToMany(targetEntity="Absence", mappedBy="user")
     */
    private $absencetable;
    /**
     * 
     * @ORM\OneToMany(targetEntity="Permission", mappedBy="user")
     */
    private $permissions;
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

 public function __construct() {
     parent::__construct();
        $this->absencetable = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }

    /**
     * Add absencetable
     *
     * @param \AppBundle\Entity\Absence $absencetable
     *
     * @return User
     */
    public function addAbsencetable(\AppBundle\Entity\Absence $absencetable)
    {
        $this->absencetable[] = $absencetable;

        return $this;
    }

    /**
     * Remove absencetable
     *
     * @param \AppBundle\Entity\Absence $absencetable
     */
    public function removeAbsencetable(\AppBundle\Entity\Absence $absencetable)
    {
        $this->absencetable->removeElement($absencetable);
    }

    /**
     * Get absencetable
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAbsencetable()
    {
        return $this->absencetable;
    }

    /**
     * Add permission
     *
     * @param \AppBundle\Entity\Permission $permission
     *
     * @return User
     */
    public function addPermission(\AppBundle\Entity\Permission $permission)
    {
        $this->permissions[] = $permission;

        return $this;
    }

    /**
     * Remove permission
     *
     * @param \AppBundle\Entity\Permission $permission
     */
    public function removePermission(\AppBundle\Entity\Permission $permission)
    {
        $this->permissions->removeElement($permission);
    }

    /**
     * Get permissions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermissions()
    {
        return $this->permissions;
    }
}
