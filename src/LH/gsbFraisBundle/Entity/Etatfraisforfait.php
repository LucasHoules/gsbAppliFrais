<?php

namespace LH\gsbFraisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etatfraisforfait
 *
 * @ORM\Table(name="etatfraisforfait")
 * @ORM\Entity(repositoryClass="LH\gsbFraisBundle\Repository\EtatfraisforfaitRepository")
 */
class Etatfraisforfait
{
    /**
     * @var string
     *
     * @ORM\Column(name="mois", type="string", length=6, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $mois;

    /**
     * @var string
     *
     * @ORM\Column(name="idVisiteur", type="string", length=4, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idVisiteur;

    /**
     * @var string
     *
     * @ORM\Column(name="idEtat", type="string", length=2, nullable=false)
     */
    private $idEtat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModif", type="date", nullable=false)
     */
    private $datemodif;



    /**
     * Set idetat
     *
     * @param string $idetat
     *
     * @return Etatfraisforfait
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
     * Set datemodif
     *
     * @param \DateTime $datemodif
     *
     * @return Etatfraisforfait
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
     * Set mois
     *
     * @param string $mois
     *
     * @return Etatfraisforfait
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
     * Set idvisiteur
     *
     * @param string $idvisiteur
     *
     * @return Etatfraisforfait
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
}
