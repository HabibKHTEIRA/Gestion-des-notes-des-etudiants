<?php

namespace App\Form;

use App\Entity\Bloc;
use App\Entity\Element;
use App\Entity\Unite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UniteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codeunite')
            ->add('nomUnite')
            ->add('coeficient')
            ->add('respunite')
            ->add('bloc', EntityType::class, [
                'class' => Bloc::class,
                'choice_label' => 'codebloc',
                'disabled' => true,
                'attr' => ['readonly' => true]
            ])
            ->add('ajouter', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Unite::class,
        ]);
    }
}
