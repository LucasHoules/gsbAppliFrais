<?php
namespace LH\gsbFraisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use LH\gsbFraisBundle\Form\Visiteur\ffType;
use LH\gsbFraisBundle\Form\Visiteur\fhfType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use LH\gsbFraisBundle\Entity\Lignefraisforfait;
use LH\gsbFraisBundle\Entity\Fichefrais;
use LH\gsbFraisBundle\Entity\Lignefraishorsforfait;
use LH\gsbFraisBundle\Entity\Visiteur;
use LH\gsbFraisBundle\Entity\Etat;

class VisiteurController extends Controller{

  public function indexAction(Request $request){
    $em = $this->getDoctrine()->getManager();
    $repositoryFicheFrais = $em->getRepository('LHgsbFraisBundle:Fichefrais');
    $listMois = $repositoryFicheFrais->getMois();
    // Ici le user a choisi un mois dans la combo box on le redirige sur la vue d'une fiche.
    if($request->isMethod('POST')){
        return $this->redirectToRoute('viewFiche' , array('moisCourant' => $_POST['mois']));
    }
    return $this->render('LHgsbFraisBundle:Visiteur:index.html.twig', array(
      'listMois' => $listMois,
    ));
  }

  public function viewAction(Request $request, $moisCourant){
    $em = $this->getDoctrine()->getManager();
    $idUser = $this->getUser()->getId(); // On récupére l'utilisateur courant
    $annee = substr($moisCourant, 3, 7);
    $mois = substr($moisCourant, 0 , 2);
    $dateSelected = $annee . '' .  $mois; // On récupére la date au courant comme elle est inscrite dans la base.
    $request->getSession()->getFlashBag()->add('ficheFrais', 'Voici la fiche frais de ce mois');

    $repositoryLigneFrais = $em->getRepository('LHgsbFraisBundle:Lignefraisforfait');
    $qteFrais = $repositoryLigneFrais->getQteFrais($dateSelected, $idUser);
    $repositoryLigneHorsForfait = $em->getRepository('LHgsbFraisBundle:Lignefraishorsforfait');
    $dataHorsForfait = $repositoryLigneHorsForfait->getInfosHorsForfait($dateSelected, $idUser);
    $repositoryEtat = $em->getRepository('LHgsbFraisBundle:Etat');
    $etat = $repositoryEtat->getEtatFicheFrais($dateSelected, $idUser);

    $repositoryFicheFrais = $em->getRepository('LHgsbFraisBundle:Fichefrais');
    $montantValide = $repositoryFicheFrais->getMontantValide($dateSelected, $idUser);
    $ficheFrais = $repositoryFicheFrais->findOneBy(array(
      'mois' => $dateSelected,
      'idvisiteur' => $idUser
    ));
    return $this->render('LHgsbFraisBundle:Visiteur:view.html.twig', array(
      'qteFrais' => $qteFrais,
      'dateSelected' => $moisCourant,
      'dataHorsForfait' => $dataHorsForfait,
      'etat' => $etat,
      'montantvalide' => $montantValide,
      'fichefrais' => $ficheFrais
    ));
  }

  public function addAction(Request $request){
      $em = $this->getDoctrine()->getManager();
      $User = $this->getUser();
      $idUser = $User->getId();
      $moisCour = $this->container->get('gsbFraisBundle.moisCourant')->getMoisCourant();
      $anneeCour = Date("Y");
      $dateCour = $anneeCour . "" . Date("m");
      $repositoryLigneFrais = $em->getRepository('LHgsbFraisBundle:Lignefraisforfait');
      $repositoryEtat = $em->getRepository('LHgsbFraisBundle:Etat');
      $formFraisForfait = $this->createForm(ffType::class);
      $formFraisHorsForfait = $this->createForm(fhfType::class);

      // Si il n'ya pas de fiche de frais pour le mois courant
      if($repositoryLigneFrais->existFicheByMonth($dateCour, $idUser) == false){
        // On clot la fiche du mois précédent
        $ficheMoisPrecedent = $repositoryFicheFrais->findOneBy(array(
          'id' => $idUser,
          'mois' => '201704'
        ));
        $Etatcloture = $repositoryEtat->findOneBy(array(
          'id' => 'CL'
        ));
        $ficheMoisPrecedent->setIdEtat($etatCloture);
        $em->persist($ficheMoisPrecedent);
        $em->flush();


        $formFraisForfait->handleRequest($request);
        if ($formFraisForfait->isSubmitted() && $formFraisForfait->isValid()){
          $lignefraisForfaitRepas = new Lignefraisforfait();
          $lignefraisForfaitNuits = new Lignefraisforfait();
          $lignefraisForfaitKms = new Lignefraisforfait();
          $lignefraisForfaitEtapes = new Lignefraisforfait();

          $Fichefrais = new Fichefrais();
          // Récupérations des données entrée par l'utilisateur
          $data = $formFraisForfait->getData();
          $qteEtapes = $data['etapes'];
          $qteKms = $data['kms'];
          $qteNuits = $data['nuits'];
          $qteRepas = $data['repas'];

          $ffTtc = $qteEtapes * 110 + $qteKms * 0.62 + $qteNuits * 80 + $qteRepas * 25;

          $Fichefrais->setMois($dateCour);
          $Fichefrais->setIdvisiteur($User);
          $Fichefrais->setMontantvalide($ffTtc);
          $Fichefrais->setNbjustificatifs(0);
          $repositoryEtat = $em->getRepository('LHgsbFraisBundle:Etat');
          $Fichefrais->setIdetat($repositoryEtat->getEtat("CR"));


          $lignefraisForfaitEtapes->setQuantite($qteEtapes);
          $lignefraisForfaitEtapes->setMois($dateCour);
          $lignefraisForfaitEtapes->setIdvisiteur($User);
          $lignefraisForfaitEtapes->setIdfraisforfait("ETP");

          $lignefraisForfaitKms->setQuantite($qteKms);
          $lignefraisForfaitKms->setMois($dateCour);
          $lignefraisForfaitKms->setIdvisiteur($User);
          $lignefraisForfaitKms->setIdfraisforfait("KM");

          $lignefraisForfaitNuits->setQuantite($qteNuits);
          $lignefraisForfaitNuits->setMois($dateCour);
          $lignefraisForfaitNuits->setIdvisiteur($User);
          $lignefraisForfaitNuits->setIdfraisforfait("NUI");

          $lignefraisForfaitRepas->setQuantite($qteRepas);
          $lignefraisForfaitRepas->setMois($dateCour);
          $lignefraisForfaitRepas->setIdvisiteur($User);
          $lignefraisForfaitRepas->setIdfraisforfait("REP");

          $em->persist($Fichefrais);
          $em->persist($lignefraisForfaitEtapes);
          $em->persist($lignefraisForfaitKms);
          $em->persist($lignefraisForfaitNuits);
          $em->persist($lignefraisForfaitRepas);
          $em->flush();
          $request->getSession()->getFlashBag()->add('success', 'Fiche frais enregistrée avec succès.');
        }
    }
    // Ici il existe déjà une fiche de frais forfait pour le mois courant.
    else{
      // Récupération des quantités des frais du mois courant.
      $repositoryLigneFrais = $em->getRepository('LHgsbFraisBundle:Lignefraisforfait');
      $repositoryFicheFrais = $em->getRepository('LHgsbFraisBundle:Fichefrais');
      $qteEtapes = $repositoryLigneFrais-> getUnQteFrais($dateCour, $idUser, 'ETP');
      $qteRep = $repositoryLigneFrais-> getUnQteFrais($dateCour, $idUser, 'REP');
      $qteNui = $repositoryLigneFrais-> getUnQteFrais($dateCour, $idUser, 'NUI');
      $qteKM = $repositoryLigneFrais-> getUnQteFrais($dateCour, $idUser, 'KM');

      // Affectation aux values du formulaire
      $formFraisForfait->get('etapes')->setData($qteEtapes['quantite']);
      $formFraisForfait->get('kms')->setData($qteKM['quantite']);
      $formFraisForfait->get('nuits')->setData($qteNui['quantite']);
      $formFraisForfait->get('repas')->setData($qteRep['quantite']);

      // Récupération des entités correspondantes
      $lignefraisForfaitEtapes = $repositoryLigneFrais->findOneBy(
        array('idvisiteur' => $idUser,
        'mois' => $dateCour,
        'idfraisforfait' => 'ETP')
      );
      $lignefraisForfaitKms = $repositoryLigneFrais->findOneBy(
        array('idvisiteur' => $idUser,
        'mois' => $dateCour,
        'idfraisforfait' => 'KM')
      );
      $lignefraisForfaitNuits = $repositoryLigneFrais->findOneBy(
        array('idvisiteur' => $idUser,
        'mois' => $dateCour,
        'idfraisforfait' => 'NUI')
      );
      $lignefraisForfaitRepas = $repositoryLigneFrais->findOneBy(
        array('idvisiteur' => $idUser,
        'mois' => $dateCour,
        'idfraisforfait' => 'REP')
      );
      $Fichefrais =  $repositoryFicheFrais->findOneBy(
        array('idvisiteur' => $idUser,
        'mois' => $dateCour
      ));
      // Gestion de la l'action "edit" du formulaire.

      $formFraisForfait->handleRequest($request);
      if ($formFraisForfait->isSubmitted() && $formFraisForfait->isValid()){
        // Récupérations des données entrée par l'utilisateur
        $data = $formFraisForfait->getData();
        $qteEtapes = $data['etapes'];
        $qteKms = $data['kms'];
        $qteNuits = $data['nuits'];
        $qteRepas = $data['repas'];

        $ffTtc = $qteEtapes * 110 + $qteKms * 0.62 + $qteNuits * 80 + $qteRepas * 25;

        // Mise à jour des quantités des entités.
        $lignefraisForfaitEtapes->setQuantite($qteEtapes);
        $lignefraisForfaitKms->setQuantite($qteKms);
        $lignefraisForfaitNuits->setQuantite($qteNuits);
        $lignefraisForfaitRepas->setQuantite($qteRepas);
        $Fichefrais->setMontantvalide($ffTtc);
        $Fichefrais->setDatemodif(new \DateTime);
        $em->persist($lignefraisForfaitEtapes);
        $em->persist($lignefraisForfaitKms);
        $em->persist($lignefraisForfaitNuits);
        $em->persist($lignefraisForfaitRepas);
        $em->persist($Fichefrais);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'Fiche frais mise à jour avec succès.');
      }
    }

    // Gestion du formulaire des frais hors forfaits.
    $formFraisHorsForfait->handleRequest($request);
    if ($formFraisHorsForfait->isSubmitted() && $formFraisHorsForfait->isValid()){
      $ligneFraisHorsForfait = new lignefraishorsforfait();
      // Récupération des données entrée par l'utilisateur
      $data = $formFraisHorsForfait->getData();
      $date = $data['Date'];
      $Libelle = $data['Libelle'];
      $Montant = $data['Montant'];

      // Hydratation de l'objet
      $ligneFraisHorsForfait->setIdvisiteur($idUser);
      $ligneFraisHorsForfait->setMois($dateCour);
      $ligneFraisHorsForfait->setLibelle($Libelle);
      $ligneFraisHorsForfait->setDate($date);
      $ligneFraisHorsForfait->setMontant($Montant);
      $em->persist($ligneFraisHorsForfait);


      $FichefraisCourante = $repositoryFicheFrais->findOneBy(array(
        'mois' => $dateCour,
        'idvisiteur' => $idUser
      ));

      // Mise à jour du montant total de la fiche de frais et sa date.
      $FichefraisCourante->setMontantValide($FichefraisCourante->getMontantValide() + $Montant);
      $FichefraisCourante->setDatemodif(new \DateTime);

      $em->flush();

      $request->getSession()->getFlashBag()->add('successHorsForfait', 'Ligne frais hors forfait enregistrée.');
  }
    return $this->render('LHgsbFraisBundle:Visiteur:add.html.twig',array(
    'formFraisForfait' => $formFraisForfait->createView(),
    'formFraisHorsForfait' => $formFraisHorsForfait->createView(),
    'mois' => $moisCour,
    'annee' => $anneeCour,

  ));
  }

  public function deleteAction(Request $request, $id){
    $em = $this->getDoctrine()->getManager();
    $user = $this->getUser();
    $repositoryHf = $em->getRepository('LHgsbFraisBundle:Lignefraishorsforfait');
    $repositoryFicheFrais = $em->getRepository('LHgsbFraisBundle:Fichefrais');
    $ficheHorsF = $repositoryHf->findOneBy(array(
      'id' => $id
    ));
    $ficheFrais = $repositoryFicheFrais->findOneBy(array(
      'mois' => $ficheHorsF->getMois(),
      'idvisiteur' => $ficheHorsF->getIdvisiteur()
    ));

    $em->remove($ficheHorsF);

    // Mise à jour du montant valide de la fiche de frais.
    $ficheFrais->setMontantvalide($ficheFrais->getMontantvalide() - $ficheHorsF->getMontant());
    $em->persist($ficheFrais);
    $moisBase = $ficheFrais->getMois();
    $year = substr($moisBase, 0, 4);
    $month = substr($moisBase, 4, 6);
    $date = $month . '/' . $year;
    $em->flush();
    return $this->redirectToRoute('viewFiche', array('moisCourant' => $date));

  }
}
