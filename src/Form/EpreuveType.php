<?php

namespace App\Form;

use App\Entity\Element;
use App\Entity\Epreuve;
use App\Entity\Matiere;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpreuveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codeepreuve')
            ->add('numChance')
            ->add('annee')
            ->add('typeEpreuve')
            ->add('salle')
            ->add('duree')
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => 'codemat',
                'disabled' => true,
                'attr' => ['readonly' => true]
            ])
            ->add('ajouter', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Epreuve::class,
        ]);
    }
}
