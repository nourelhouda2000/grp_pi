<?php

namespace App\Form;

use App\Entity\Rendezvous;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

use Symfony\Component\Form\Extension\Core\Type\DateType;
class RendezvousType extends AbstractType
{

   
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


       
        $builder
        ->add('dateR')
            
            
        ->add('heur')
        
        ->add('idUser', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'idUser', 
            'label' => 'ID Utilisateur',
            'attr' => ['class' => 'form-control'],])
            
            ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rendezvous::class,
            'idUser' => null,
            
        ]);
    }
}
