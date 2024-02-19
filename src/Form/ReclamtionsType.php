<?php

namespace App\Form;

use App\Entity\Reclamations;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; // Importez la classe ChoiceType
use Symfony\Component\Form\Extension\Core\Type\TextType;
class ReclamtionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class,
            )
            ->add('daterec')
            ->add('iduser')
            ->add('Priorite', ChoiceType::class, [
                'choices' => [
                    'haute' => 'haute',
                    'moyenne' => 'moyenne',
                    'basse' => 'basse',
                ],
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamations::class,
        ]);
    }
}
