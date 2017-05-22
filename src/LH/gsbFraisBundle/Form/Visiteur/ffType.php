<?php
namespace LH\gsbFraisBundle\Form\Visiteur;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
* Formulaire des frais forfaits
*
*/
class ffType extends AbstractType{

  public function buildForm(FormBuilderInterface $builder, array $options){
    $builder
    ->add('etapes', IntegerType::class)
    ->add('kms', IntegerType::class)
    ->add('nuits', IntegerType::class)
    ->add('repas', IntegerType::class)
    ->add('valider', SubmitType::class)
    ->add('annuler', ResetType::class);
  }
}
