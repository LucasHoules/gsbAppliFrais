<?php
namespace LH\gsbFraisBundle\Form\Visiteur;

use Doctrine\ORM\EntityRepository;
use LH\gsbFraisBundle\Repository\FichefraisRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class moisType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options){
    $builder
      ->add('mois', EntityType::class, array(
        'class' => 'LH\gsbFraisBundle\Entity\Fichefrais',
        'choice_label' => 'mois',
        'query_builder' => function(FichefraisRepository $repository) {
            return $repository->getMois();
          }
      ))
      ->add('Valider', SubmitType::class)
      ->add('Effacer',ButtonType::class );
  }

}
