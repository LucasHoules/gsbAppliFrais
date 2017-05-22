<?php

namespace LH\gsbFraisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Situation
 *
 * @ORM\Table(name="situation")
 * @ORM\Entity
 */
class Situation
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=2, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=20, nullable=false)
     */
    private $libelle;



    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Situation
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
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
