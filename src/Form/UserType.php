<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, ['label' => '*Email'])
            ->add('firstname', null, ['label' => '*Prénom'])
            ->add('lastname', null, ['label' => '*Nom'])
            ->add('password', PasswordType::class, [
                'label' => '*Mot de passe',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le mot de passe est obligatoire.']),
                ],
            ])
            ->add('confirm_password', PasswordType::class, [
                'label' => '*Confirmer le mot de passe',
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La confirmation du mot de passe est obligatoire.']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [
                new Assert\Callback([$this, 'validatePasswords']),
            ],
        ]);
    }

    public function validatePasswords(User $user, ExecutionContextInterface $context): void
    {
        $form = $context->getRoot();
        $password = $form->get('password')->getData();
        $confirmPassword = $form->get('confirm_password')->getData();

        if ($password !== $confirmPassword) {
            $context->buildViolation('Les mots de passe ne correspondent pas.')
                ->atPath('confirm_password')
                ->addViolation();
        }
    }
}
