<?php

namespace LH\gsbFraisBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class lignefraishorsforfaitRepository extends EntityRepository
{
  /**
   * Fonction qui permet de récupérer
   * les informations d'une ligne frais hors forfait pour  un visiteur et un mois donnée.
   * @return array
   */
  public function getInfosHorsForfait($mois, $visiteur){
      $qb = $this->_em->createQueryBuilder()
      ->select('l')
      ->from($this->_entityName, 'l')
      ->andWhere('l.mois = :mois')
        ->setParameter('mois', $mois)
      ->andWhere('l.idvisiteur = :idvisiteur')
        ->setParameter('idvisiteur', $visiteur);
      return $qb->getQuery()->getResult();
  }


}
