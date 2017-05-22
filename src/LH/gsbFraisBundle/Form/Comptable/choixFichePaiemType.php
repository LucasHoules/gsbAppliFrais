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

class choixFichePaiemType extends AbstractType{

  public function buildForm(FormBuilderInterface $builder, array $options){
    $builder->add('ficheFraisValider', EntityType::class, array(
      'class' => 'LHgsbFraisBundle:Fichefrais',
      'query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('f')
            ->select('f')
            ->where('f.idetat = :idetat')
              ->setParameter('idetat', 'VA')
            ->orderBy('f.mois', 'DESC');
    },
    'choice_label' => 'montantvalide'


    ))
    ->add('valider', SubmitType::class)
    ->add('annuler', ResetType::class);

  }

}
