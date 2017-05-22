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

class ffType extends AbstractType{
  public function buildForm($FormBuilderInterface $builder, array $options){
    $builder->add('fraisForfait',EntityType::class, array(
      'class' => 'LHgsbFraisBundle:Fichefrais'
    ))
    ->add('valider', SubmitType::class)
    ->add('annuler', ResetType::class);

  }
}
