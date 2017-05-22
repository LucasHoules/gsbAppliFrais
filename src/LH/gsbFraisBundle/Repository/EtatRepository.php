<?php

namespace LH\gsbFraisBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class EtatRepository extends EntityRepository
{
  /**
   * Fonction qui permet de récupérer un état suivant le paramètre donné.
   * @return Etat
   */

   function getEtat($etat){
     $qb = $this->_em->createQueryBuilder()
     ->select('E')
     ->from($this->_entityName, 'E')
     ->Where('E.id = :id')
      ->setParameter('id', $etat);
    return $qb->getQuery()->getSingleResult();
   }

   /**
    * Fonction qui permet de récupérer un état suivant un visiteur et une date donnée.
    * @return Etat
    */

   function getEtatFicheFrais($mois, $visiteur){
     $query = $this->_em->createQuery("Select E.id, E.libelle from LHgsbFraisBundle:Fichefrais F JOIN F.idetat E
     Where F.idvisiteur = :visiteur AND F.mois = :mois");
     $query->setParameters(array(
       'visiteur'=> $visiteur,
        'mois' => $mois
      ));
     return $query->getSingleResult();
   }


}
