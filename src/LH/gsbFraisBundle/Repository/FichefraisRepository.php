<?php

namespace LH\gsbFraisBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class FichefraisRepository extends EntityRepository
{
  /**
   * Fonction qui permet de récupérer
   * tous les mois (sans doublon) pour lesquels
   * une fiche de frais existe
   * @return array
   */
  public function getMois()
  {

    $qb = $this->_em->createQueryBuilder()
       ->select('f.mois')
       ->from($this->_entityName, 'f')
       ->orderBy('f.mois', 'DESC')
       ->distinct();
    $array_result = $qb->getQuery()->getResult();
    return $this->stringConversionMois($array_result);
  }

  /**
    * Fonction de conversion des mois en string
    * @param $array_result
    * @return array
    */
   private function stringConversionMois($array_result)
   {
       $arr = array();
       foreach ($array_result as $key => $subArray) {
           $year = substr($subArray['mois'], 0, 4); // l'année etant les 4 premiers caractères de la chaîne
           $month = substr($subArray['mois'], 4, 6); // Le mois etant les 2 derniers
           $arr[$subArray['mois']] = $month . '/' . $year;
       }
       return $arr;
   }

   /**
   * Fonction qui retourne le montant total valide d'une fiche de frais
   */

   public function getMontantValide($mois, $visiteur){
     $qb = $this->_em->createQueryBuilder()
     ->select('f.montantvalide')
     ->from($this->_entityName, 'f')
     ->where('f.idvisiteur = :visiteur')
      ->setParameter('visiteur', $visiteur)
     ->andWhere('f.mois = :mois')
      ->setParameter('mois', $mois);
     return $qb->getQuery()->getSingleResult();
   }










}
