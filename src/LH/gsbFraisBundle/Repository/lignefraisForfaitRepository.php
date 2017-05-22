<?php

namespace LH\gsbFraisBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class lignefraisForfaitRepository extends EntityRepository
{
  /**
   * Fonction qui permet de récupérer
   * les quantité des frais de la ligne de frais forfait pour  un visiteur et un mois donnée.
   * @return array
   */
  public function getQteFrais($mois, $visiteur){

      $qb = $this->_em->createQueryBuilder()
      ->select('l.quantite, l.idfraisforfait')
      ->from($this->_entityName, 'l')
      ->andWhere('l.mois = :mois')
        ->setParameter('mois', $mois)
      ->andWhere('l.idvisiteur = :idvisiteur')
        ->setParameter('idvisiteur', $visiteur);
      return $qb->getQuery()->getResult();
  }

  /**
   * Fonction qui permet de récupérer
   * les quantité des frais de la ligne de frais forfait pour  un visiteur et un mois donnée.
   * @return array
   */
  public function getUnQteFrais($mois, $visiteur, $libellefrais){

      $query = $this->_em->createQuery
      ('select L.quantite from LHgsbFraisBundle:Lignefraisforfait L
       where L.idvisiteur = :idvisiteur AND L.mois = :mois AND L.idfraisforfait = :libellefrais');
       $query->setParameters(array(
         'idvisiteur' => $visiteur,
         'mois' => $mois,
         'libellefrais' => $libellefrais
       ));
      return $query->getSingleResult();
  }
  /**
   * Fonction qui permet de savoir si une fiche de frais existe pour le mois en cours
   *
   * @return boolean
   */
  public function existFicheByMonth($dateCour, $idVisiteur){
   $exist = false;
    $qb = $this->_em->createQueryBuilder()
       ->select('l')
       ->from($this->_entityName, 'l')
       ->where('l.mois = :dateCourante')
         ->setParameter('dateCourante', $dateCour)
       ->andWhere('l.idvisiteur = :visiteur')
         ->setParameter('visiteur', $idVisiteur);
    if($qb->getQuery()->getResult() != null){
      $exist = true;
    }
    return $exist;
  }
}
