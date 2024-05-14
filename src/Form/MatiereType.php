<?php

namespace App\Form;

use App\Entity\Element;
use App\Entity\Matiere;
use App\Entity\Unite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatiereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codemat')
            ->add('nomMat')
            ->add('periode')
            ->add('unite', EntityType::class, [
                'class' => Unite::class,
                'choice_label' => 'codeunite',
                'disabled' => true,
                'attr' => ['readonly' => true]
            ])
            ->add('ajouter', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matiere::class,
        ]);
    }
}
