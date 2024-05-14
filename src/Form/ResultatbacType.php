<?php

namespace App\Form;

use App\Entity\Bac;
use App\Entity\Etudiant;
use App\Entity\Resultatbac;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultatbacType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add('etudiant', EntityType::class, [
            'class' => Etudiant::class,
            'choice_label' => 'numetd'
           ]
            )
            ->add('bac', EntityType::class, [
                'class' => Bac::class,
                'choice_label' => 'typebac', 
            ])
            ->add('anneebac')
            ->add('mention')
            ->add('moyennebac')

            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Resultatbac::class,
        ]);
    }
}
