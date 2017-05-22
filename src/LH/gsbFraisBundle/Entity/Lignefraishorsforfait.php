<?php

namespace LH\gsbFraisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lignefraishorsforfait
 *
 * @ORM\Table(name="lignefraishorsforfait", indexes={@ORM\Index(name="idVisiteur", columns={"idVisiteur", "mois"})})
 * @ORM\Entity(repositoryClass="LH\gsbFraisBundle\Repository\lignefraishorsforfaitRepository")
 */
class Lignefraishorsforfait
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="idVisiteur", type="string", length=4, nullable=false)
     * @ORM\OneToOne(targetEntity="Visiteur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idVisiteur", referencedColumnName="id")
     * })
     */
    private $idvisiteur;


    /**
     * @var string
     *
     * @ORM\Column(name="mois", type="string", length=6, nullable=false)
     */
    private $mois;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=100, nullable=true)
     */
    private $libelle;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModif", type="date", nullable=false)
     */
    private $datemodif;

    /**
     * @var string
     *
     * @ORM\Column(name="idEtat", type="string", length=2, nullable=true)
     */
    private $idetat = 'CR';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->datemodif = new \DateTime;
    }


    /**
     * Set idvisiteur
     *
     * @param string $idvisiteur
     *
     * @return Lignefraishorsforfait
     */
    public function setIdvisiteur($idvisiteur)
    {
        $this->idvisiteur = $idvisiteur;

        return $this;
    }

    /**
     * Get idvisiteur
     *
     * @return string
     */
    public function getIdvisiteur()
    {
        return $this->idvisiteur;
    }

    /**
     * Set mois
     *
     * @param string $mois
     *
     * @return Lignefraishorsforfait
     */
    public function setMois($mois)
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * Get mois
     *
     * @return string
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Lignefraishorsforfait
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Lignefraishorsforfait
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

    /**
     * Set montant
     *
     * @param string $montant
     *
     * @return Lignefraishorsforfait
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set datemodif
     *
     * @param \DateTime $datemodif
     *
     * @return Lignefraishorsforfait
     */
    public function setDatemodif($datemodif)
    {
        $this->datemodif = $datemodif;

        return $this;
    }

    /**
     * Get datemodif
     *
     * @return \DateTime
     */
    public function getDatemodif()
    {
        return $this->datemodif;
    }

    /**
     * Set idetat
     *
     * @param string $idetat
     *
     * @return Lignefraishorsforfait
     */
    public function setIdetat($idetat)
    {
        $this->idetat = $idetat;

        return $this;
    }

    /**
     * Get idetat
     *
     * @return string
     */
    public function getIdetat()
    {
        return $this->idetat;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
