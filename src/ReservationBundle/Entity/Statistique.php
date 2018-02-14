<?php

namespace ReservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Statistique
 *
 * @ORM\Table(name="statistique")
 * @ORM\Entity(repositoryClass="ReservationBundle\Repository\StatistiqueRepository")
 */
class Statistique
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
     * @ORM\Column(name="nb_billet_vendu", type="string", length=255)
     * @Assert\NotBlank()
     *
     */
    private $nbBilletVendu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private  $date;
    

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
     * Set nbBilletVendu
     *
     * @param string $nbBilletVendu
     *
     * @return Statistique
     */
    public function setNbBilletVendu($nbBilletVendu)
    {
        $this->nbBilletVendu = $nbBilletVendu;

        return $this;
    }

    /**
     * Get nbBilletVendu
     *
     * @return string
     */
    public function getNbBilletVendu()
    {
        return $this->nbBilletVendu;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Statistique
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
