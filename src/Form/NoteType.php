<?php

namespace App\Form;

use App\Entity\AnneeUniversitaire;
use App\Entity\Element;
use App\Entity\Etudiant;
use App\Entity\Note;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note')
            ->add('anneeuniversitaire', EntityType::class, [
                'class' => AnneeUniversitaire::class,
                'choice_label' => 'annee',
            ])
            ->add('etudiant', EntityType::class, [
                'class' => Etudiant::class,
                'choice_label' => 'numetd',
            ])
            ->add('element', EntityType::class, [
                'class' => Element::class,
                'choice_label' => 'codeelt',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
