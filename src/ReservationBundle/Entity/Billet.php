<?php

namespace ReservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Billet
 *
 * @ORM\Table(name="billet")
 * @ORM\Entity(repositoryClass="ReservationBundle\Repository\BilletRepository")
 */
class Billet
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
     * @ORM\Column(name="code_de_reservation", type="string", length=255)
     */
    private $codeDeReservation;

    /**
     * @var string
     *
     * @ORM\Column(name="type_billet", type="string", length=255)
     */
    private $typeBillet;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255)
     */
    private $pays;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_de_naissance", type="date", length=255)
     */
    private $date_de_naissance;

    /**
     * @ORM\ManyToOne(targetEntity="ReservationBundle\Entity\Tarrif", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */

    private $tarrifs;




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
     * Set codeDeReservation
     *
     * @param string $codeDeReservation
     *
     * @return Billet
     */
    public function setCodeDeReservation($codeDeReservation)
    {
        $this->codeDeReservation = $codeDeReservation;

        return $this;
    }

    /**
     * Get codeDeReservation
     *
     * @return string
     */
    public function getCodeDeReservation()
    {
        return $this->codeDeReservation;
    }

    /**
     * Set typeBillet
     *
     * @param string $typeBillet
     *
     * @return Billet
     */
    public function setTypeBillet($typeBillet)
    {
        $this->typeBillet = $typeBillet;

        return $this;
    }

    /**
     * Get typeBillet
     *
     * @return string
     */
    public function getTypeBillet()
    {
        return $this->typeBillet;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Billet
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Billet
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Billet
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set dateDeNaissance
     *
     * @param \DateTime $dateDeNaissance
     *
     * @return Billet
     */
    public function setDateDeNaissance($dateDeNaissance)
    {
        $this->date_de_naissance = $dateDeNaissance;

        return $this;
    }

    /**
     * Get dateDeNaissance
     *
     * @return \DateTime
     */
    public function getDateDeNaissance()
    {
        return $this->date_de_naissance;
    }

    /**
     * Set tarrifs
     *
     * @param \ReservationBundle\Entity\Tarrif $tarrifs
     *
     * @return Billet
     */
    public function setTarrifs(\ReservationBundle\Entity\Tarrif $tarrifs)
    {
        $this->tarrifs = $tarrifs;

        return $this;
    }

    /**
     * Get tarrifs
     *
     * @return \ReservationBundle\Entity\Tarrif
     */
    public function getTarrifs()
    {
        return $this->tarrifs;
    }
}
