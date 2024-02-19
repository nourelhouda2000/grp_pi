<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Reclamations;
use App\Entity\Reponses;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; 
use Symfony\Component\Form\Extension\Core\Type\TextType;// Importez la classe ChoiceType
class ReponsesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('description', TextType::class,
        )
            ->add('daterec')
            ->add('reclamations')
            ->add('notereponse', ChoiceType::class, [
                'choices' => [
                    'positive' => 'positive',
                    'moyenne' => 'moyenne',
                    'excellente' => 'excellente',
                ],
                'attr' => ['class' => 'form-control']
            ]);
            
            
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponses::class,
        ]);
    }
}
