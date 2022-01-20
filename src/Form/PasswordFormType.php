<?php

namespace App\Form;

use App\Entity\PasswordProfil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'input2',
                    'placeholder' => "Mot de passe",
                ]
            ])
            ->add('newPasswordProfil', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'input2',
                    'placeholder' => "Nouveau  mot de passe",
                ]
            ])
            ->add('confirmPasswordProfil', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'input2',
                    'placeholder' => "Confirmer votre nouveau mot de passe",
                ]
            ])
            ->add('Enregistrer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn haut'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PasswordProfil::class,
        ]);
    }
}
