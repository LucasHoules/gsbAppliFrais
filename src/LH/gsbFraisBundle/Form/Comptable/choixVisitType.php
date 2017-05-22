<?php
namespace LH\gsbFraisBundle\Form\Comptable;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class choixVisitType extends AbstractType{
  public function buildForm(FormBuilderInterface $builder, array $options){
    $builder->add('Visiteur', EntityType::class, array(
      'class' => 'LHgsbFraisBundle:Visiteur',
      'choice_label' => 'nom',
    ))
    ->add('Mois', EntityType::class, array(
      'class' => 'LHgsbFraisBundle:Fichefrais',
      'choice_label' => 'mois',
      'query_builder' =>  function (EntityRepository $er){
        return $er->createQueryBuilder('f')
        ->orderBy('f.mois', 'DESC')
        ->groupBy('f.mois');
      }

    ))
    ->add('valider', SubmitType::class)
    ->add('annuler', ResetType::class);

  }

}
