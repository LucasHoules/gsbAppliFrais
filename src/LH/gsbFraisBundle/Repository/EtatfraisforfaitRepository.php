<?php

namespace LH\gsbFraisBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class EtatfraisforfaitRepository extends EntityRepository
{
  /**
  * Procédure transformant un état de la base de donnée en son libelle courant.
  */
  public function transformEtat($etat){
    if($etat == "CL"){
      $etatRetour = "Saisie clôturée";
    }
    if($etat == "CR"){
      $etatRetour = "Fiche créée, saisie en cours";
    }
    return $etatRetour;

  }
}
