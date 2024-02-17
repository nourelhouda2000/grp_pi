<?php

namespace App\Form;

use App\Entity\Analyses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AnalysesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('poids')
            ->add('taille')
            ->add('poidsideal')
            ->add('IMC')
            ->add('taux')
            ->add('Sante')
            
            
            ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Analyses::class,
        ]);
    }
}
