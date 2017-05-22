<?php

namespace LH\gsbFraisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lignefraisforfait
 *
 * @ORM\Table(name="lignefraisforfait", indexes={@ORM\Index(name="idFraisForfait", columns={"idFraisForfait"})})
 * @ORM\Entity(repositoryClass="LH\gsbFraisBundle\Repository\lignefraisForfaitRepository")
 */
class Lignefraisforfait
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
     * @var \Visiteur
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Visiteur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idVisiteur", referencedColumnName="id")
     * })
     */
    private $idvisiteur;

    /**
     * @var string
     *
     * @ORM\Column(name="idFraisForfait", type="string", length=3, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="Fraisforfait")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $idfraisforfait;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer", nullable=true)
     */
    private $quantite;



    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return Lignefraisforfait
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set mois
     *
     * @param string $mois
     *
     * @return Lignefraisforfait
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
     * @return Lignefraisforfait
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
     * Set idfraisforfait
     *
     * @param string $idfraisforfait
     *
     * @return Lignefraisforfait
     */
    public function setIdfraisforfait($idfraisforfait)
    {
        $this->idfraisforfait = $idfraisforfait;

        return $this;
    }

    /**
     * Get idfraisforfait
     *
     * @return string
     */
    public function getIdfraisforfait()
    {
        return $this->idfraisforfait;
    }
}
