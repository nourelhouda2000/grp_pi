<?php

namespace App\Form;

use App\Entity\Exercice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExerciceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('niveau', ChoiceType::class, [
                'choices' => [
                    'Facile' => 'facile',
                    'Modéré' => 'modéré',
                    'Difficile' => 'difficile',
                ],
                'required' => true,
                'placeholder' => 'Choisir le niveau',
            ])
            ->add('nombreRepetition')
            ->add('Activite')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercice::class,
        ]);
    }
}
