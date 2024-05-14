<?php

namespace App\Form;

use App\Entity\Bloc;
use App\Entity\Element;
use App\Entity\Epreuve;
use App\Entity\Filiere;
use App\Entity\Matiere;
use App\Entity\Unite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ElementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codeelt')
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => 'codemat',
            ])
            ->add('unite', EntityType::class, [
                'class' => Unite::class,
                'choice_label' => 'codeunite',
            ])
            ->add('bloc', EntityType::class, [
                'class' => Bloc::class,
                'choice_label' => 'codebloc',
            ])
            ->add('filiere', EntityType::class, [
                'class' => Filiere::class,
                'choice_label' => 'codefiliere',
            ])
            ->add('epreuve', EntityType::class, [
                'class' => Epreuve::class,
                'choice_label' => 'codeepreuve',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Element::class,
        ]);
    }
}
