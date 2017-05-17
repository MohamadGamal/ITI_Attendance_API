<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rules
 *
 * @ORM\Table(name="rules")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RulesRepository")
 */
class Rules
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
     * @ORM\Column(name="days", type="integer")
     */
    private $days;

    /**
     * @var float
     *
     * @ORM\Column(name="marks", type="float")
     */
    private $marks;

    /**
     * @var float
     *
     * @ORM\Column(name="perdaynext", type="float", nullable=true)
     */
    private $perdaynext;


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
     * Set days
     *
     * @param integer $days
     *
     * @return Rules
     */
    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    /**
     * Get days
     *
     * @return int
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Set marks
     *
     * @param float $marks
     *
     * @return Rules
     */
    public function setMarks($marks)
    {
        $this->marks = $marks;

        return $this;
    }

    /**
     * Get marks
     *
     * @return float
     */
    public function getMarks()
    {
        return $this->marks;
    }

    /**
     * Set perdaynext
     *
     * @param float $perdaynext
     *
     * @return Rules
     */
    public function setPerdaynext($perdaynext)
    {
        $this->perdaynext = $perdaynext;

        return $this;
    }

    /**
     * Get perdaynext
     *
     * @return float
     */
    public function getPerdaynext()
    {
        return $this->perdaynext;
    }
}
