<?php

namespace LH\gsbFraisBundle\Date;

class dateCourante{

  /**
  *
  * Procédure retournant le libelle du mois courant
  *
  *
  */
  public function getMoisCourant(){

    switch(date("m")){
      case 01:
        $moisCourant = "Janvier";
        break;
      case 02:
        $moisCourant = "Février";
        break;
      case 03:
        $moisCourant = "Mars";
        break;
      case 04:
        $moisCourant = "Avril";
        break;
      case 05:
        $moisCourant = "Mai";
        break;
      case 06:
        $moisCourant = "Juin";
        break;
      case 07:
        $moisCourant = "Juillet";
        break;
      case 08:
        $moisCourant = "Août";
        break;
      case 09:
        $moisCourant = "Septembre";
        break;
      case 10:
        $moisCourant = "Octobre";
        break;
      case 11:
        $moisCourant = "Novembre";
        break;
      case 12:
        $moisCourant = "Décembre";
        break;
    }
    return $moisCourant;

  }
}
