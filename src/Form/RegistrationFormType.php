<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'input1',
                    'placeholder' => "Nom d'utilisateur",
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'input1',
                    'placeholder' => "Email",
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'input1',
                    'placeholder' => "Mot de passe",
                ]
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'input1',
                    'placeholder' => "Confirmer votre mot de passe",
                ]
            ])
            ->add('Enregistrer', SubmitType::class, [
                'attr' => [
                    'class' => 'btnCo haut'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
