<?php

namespace ReservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="ReservationBundle\Repository\ReservationRepository")
 */
class Reservation
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
     * @var \DateTime
     *
     * @ORM\Column(name="jour", type="date", length=255)
     */
    private $jour;

    /**
     * @ORM\ManyToOne(targetEntity="ReservationBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="ReservationBundle\Entity\Billet")
     * @ORM\JoinColumn(nullable=false)
     */

    private $billets;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set jour
     *
     * @param \DateTime $jour
     *
     * @return Reservation
     */
    public function setJour($jour)
    {
        $this->jour = $jour;

        return $this;
    }

    /**
     * Get jour
     *
     * @return \DateTime
     */
    public function getJour()
    {
        return $this->jour;
    }

    /**
     * Set user
     *
     * @param \ReservationBundle\Entity\User $user
     *
     * @return Reservation
     */
    public function setUser(\ReservationBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ReservationBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set billets
     *
     * @param \ReservationBundle\Entity\Billet $billets
     *
     * @return Reservation
     */
    public function setBillets(\ReservationBundle\Entity\Billet $billets)
    {
        $this->billets = $billets;

        return $this;
    }

    /**
     * Get billets
     *
     * @return \ReservationBundle\Entity\Billet
     */
    public function getBillets()
    {
        return $this->billets;
    }
}
