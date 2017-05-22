<?php
namespace LH\gsbFraisBundle\Form\Visiteur;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
/**
* Formulaire des frais hors forfaits
*
*/
class fhfType extends AbstractType{

  public function buildForm(FormBuilderInterface $builder, array $options){
    $builder
    ->add('Date', DateType::class, array(
      'attr' => ['class' => 'js-datepicker'],
      'widget' => 'choice',
    'format' => 'yyyy-MM-dd'
    ))
    ->add('Libelle', TextType::class)
    ->add('Montant', NumberType::class, array(
      'invalid_message' => 'You entered an invalid value, it should include %num% letters'
    ))
    ->add('Ajouter', SubmitType::class)
    ->add('Effacer', ResetType::class);
  }
}
