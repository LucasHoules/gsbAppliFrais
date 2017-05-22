<?php
namespace LH\gsbFraisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use LH\gsbFraisBundle\Form\Comptable\choixVisitType;
use LH\gsbFraisBundle\Form\Comptable\choixFichePaiemType;
use LH\gsbFraisBundle\Entity\Lignefraisforfait;
use LH\gsbFraisBundle\Entity\Fichefrais;
use LH\gsbFraisBundle\Entity\Lignefraishorsforfait;
use LH\gsbFraisBundle\Entity\Visiteur;
use LH\gsbFraisBundle\Entity\Etat;

class ComptableController extends Controller{

  public function choixVisiteurAction(Request $request){
    $form = $this->createForm(choixVisitType::class);
    // Si le formulaire a été soumis
    $form->handleRequest($request);
      if($request->isMethod('POST')){
      // On récupére les données entrée par le comptable
        $data = $form->getData();
        $visiteurId = $data['Visiteur']->getId();
        $mois = $data['Mois']->getMois();
        $em = $this->getDoctrine()->getManager();
        $repositoryFicheFrais = $em->getRepository('LHgsbFraisBundle:Fichefrais');
        $fichefrais = $repositoryFicheFrais->findOneBy(array(
          'mois' => $mois,
          'idvisiteur' => $visiteurId
        ));
       $existFiche = $repositoryFicheFrais->findBy(array(
         'mois' => $mois,
         'idvisiteur' => $visiteurId
       ));

        // Si la fiche de frais demandé existe on redirige sur la validation.
        if($existFiche )
        {
          return $this->redirectToRoute('validFiche', array(
            'mois' => $mois,
            'idvisiteur' => $visiteurId
          ));
        }
        // Ici il n'existe aucune fiche de frais .
        else{
            $request->getSession()->getFlashBag()->add('Error', 'Aucune fiche de frais pour ce visiteur et ce mois donnée.');
        }
    }
    return $this->render('LHgsbFraisBundle:Comptable:choixVisiteur.html.twig', array(
      'form' => $form->createView()
    ));

  }

  public function validAction(Request $request, $idvisiteur, $mois){

    $em = $this->getDoctrine()->getManager();
    // Récupération de la fiche de frais avec ses frais forfaits et hors forfaits.
    $repositoryLigneFrais = $em->getRepository('LHgsbFraisBundle:Lignefraisforfait');
    $qteFrais = $repositoryLigneFrais->getQteFrais($mois, $idvisiteur);
    $repositoryLigneHorsForfait = $em->getRepository('LHgsbFraisBundle:Lignefraishorsforfait');
    $dataHorsForfait = $repositoryLigneHorsForfait->getInfosHorsForfait($mois, $idvisiteur);
    $repositoryEtat = $em->getRepository('LHgsbFraisBundle:Etat');
    $Etatcloture = $repositoryEtat->findOneBy(array(
      'id' => 'CL'
    ));
    $repositoryFicheFrais = $em->getRepository('LHgsbFraisBundle:Fichefrais');
    $montantValide = $repositoryFicheFrais->getMontantValide($mois, $idvisiteur);
    $fichefrais = $repositoryFicheFrais->findOneBy(array(
      'mois' => $mois,
      'idvisiteur' => $idvisiteur
    ));
    $etatFiche = $fichefrais->getIdetat();
    // Cloture de la fiche de frais si cela n'a pas déjà été fait.
    if($etatFiche->getId() == 'CR'){ // Si la fiche a encore l'état saisie en cours.
      $fichefrais->setIdetat($Etatcloture);
      $em->persist($fichefrais);
      $em->flush();
    }

    $repositoryVisiteur = $em->getRepository('LHgsbFraisBundle:Visiteur');
    $visiteur = $repositoryVisiteur->findOneBy(array(
      'id' => $idvisiteur
    ));



    return $this->render('LHgsbFraisBundle:Comptable:validFiche.html.twig', array(
      'mois' => $mois,
      'qteFrais' => $qteFrais,
      'dataHorsForfait' => $dataHorsForfait,
      'etat' => $etatFiche,
      'montantvalide' => $montantValide,
      'visiteur' => $visiteur,
      'idvisiteur' => $idvisiteur,
      'fichefrais' => $fichefrais,

    ));

  }
  public function validationAction(Request $request, $mois,$idvisiteur){
    $em = $this->getDoctrine()->getManager();
    // Récupération de l'état valide.
    $repositoryEtat = $em->getRepository('LHgsbFraisBundle:Etat');
    $etatValide = $repositoryEtat->findOneBy(array(
      'id' => 'VA'
    ));
    // Récupération de la fiche de frais
    $repositoryFicheFrais = $em->getRepository('LHgsbFraisBundle:Fichefrais');
    $ficheFrais = $repositoryFicheFrais->findOneBy(array(
      'mois' => $mois,
      'idvisiteur' => $idvisiteur
    ));

    if($ficheFrais->getIdEtat()->getId() != "VA" && $ficheFrais->getIdEtat()->getId() != "RB"){
      $ficheFrais->setIdEtat($etatValide);
      $ficheFrais->setDatemodif(new \DateTime);
      $em->persist($ficheFrais);
      $em->flush();
      $request->getSession()->getFlashBag()->add('success', 'Validation de la fiche de frais effectuées avec succès');
  }
  else if ($ficheFrais->getIdEtat()->getId() == "VA" || $ficheFrais->getIdEtat()->getId() == "RB" ){
    $request->getSession()->getFlashBag()->add('error', 'Cette fiche de frais a déjà été valider !');
  }
    return $this->redirectToRoute('validFiche', array('mois' => $mois, 'idvisiteur' => $idvisiteur));
  }

  // Action permettant de refuser un frais hors forfait
  public function refusAction(Request $request, $id){
    $em = $this->getDoctrine()->getManager();
    $repositoryLigneHorsForfait = $em->getRepository('LHgsbFraisBundle:Lignefraishorsforfait');
    $repositoryFicheFrais = $em->getRepository('LHgsbFraisBundle:Fichefrais');

    $ligneHf = $repositoryLigneHorsForfait->findOneBy(array(
      'id' => $id
    ));
    $Fichefrais = $repositoryFicheFrais->findOneBy(array(
      'mois' => $ligneHf->getMois(),
      'idvisiteur' => $ligneHf->getidvisiteur()
    ));
    // Mise à jour du montant de la fiche de frais.

    if($ligneHf->getIdetat() != "RF" && $Fichefrais->getIdetat() != "VA" && $Fichefrais->getIdetat() != "RB")
    {
      $Fichefrais->setMontantValide($Fichefrais->getMontantValide() - $ligneHf->getMontant());
      $ligneHf->setDatemodif(new \DateTime);
      $ligneHf->setLibelle("REFUSE - ". $ligneHf->getLibelle());
      $ligneHf->setIdetat("RF");
      $em->persist($Fichefrais);
      $em->persist($ligneHf);
      $em->flush();
      $request->getSession()->getFlashBag()->add('success', 'frais hors forfait refusé avec succès !');
    }
    else if ($Fichefrais->getIdetat() == "VA" || $Fichefrais->getIdetat() == "RB"){
      $request->getSession()->getFlashBag()->add('error', 'Vous ne pouvez pas refuser un frais d\'une fiche déjà validée !');
    }
    else{
      $request->getSession()->getFlashBag()->add('Error', 'frais hors forfait déjà refusé !');
    }
    return $this->redirectToRoute('validFiche', array(
      'mois' => $ligneHf->getMois(),
      'idvisiteur' => $ligneHf->getidvisiteur()
    ));

  }

  public function suiviPaiementAction(Request $request){

    $em = $this->getDoctrine()->getManager();
    $repositoryFicheFrais = $em->getRepository('LHgsbFraisBundle:Fichefrais');
    //Ensemble des fiche de frais qui ont l'état "valider".
    $fichefraisValider = $repositoryFicheFrais->findBy(array(
      'idetat' => "VA"
    ));
    return $this->render('LHgsbFraisBundle:Comptable:suivPaiement.html.twig', array(
      'ffValid' => $fichefraisValider
    ));

  }

  public function rembourserAction(Request $request, $idvisiteur, $mois){
    $em = $this->getDoctrine()->getManager();
    $repositoryFicheFrais = $em->getRepository('LHgsbFraisBundle:Fichefrais');
    $repositoryEtat = $em->getRepository('LHgsbFraisBundle:Etat');
    $fichefrais = $repositoryFicheFrais->findOneBy(array(
      'idvisiteur' => $idvisiteur,
      'mois' => $mois
     ));
     $etatRembourser = $repositoryEtat->findOneBy(array(
       'id' => 'RB'
     ));
     $fichefrais->setIdEtat($etatRembourser);
     $fichefrais->setMontantValide(0);
     $fichefrais->setDatemodif(new \DateTime);
     $em->persist($fichefrais);
     $em->flush();
     $request->getSession()->getFlashBag()->add('success', 'fiche de frais mise en paiement avec succès !');
     return $this->redirectToRoute('suiviPaiement');

  }
}
