<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\Group;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class TrickFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Titre du trick',
                    'class' => 'input2',
                ],
            ])
            ->add('groupe', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'name',
                'label' => 'Groupe',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description du trick',
                    'class' => 'input2',
                ],
            ])
            ->add('imageMain', FileType::class,  [
                'label' => 'Image principale',
                'mapped' => false,
                'required' => false,
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                    ])
                ],
                'attr' => [
                    'class' => 'input2',
                ],
            ])
            ->add('images', CollectionType::class, [
                'label' => 'Choisir une ou des image(s)',
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                //'by_reference' => false,
            ])
            // ->add('media', FileType::class, [
            //     'label' => 'Choisir une ou des image(s)',
            //     'mapped' => false,
            //     'required' => false,
            //     //'multiple' => true,
            //     'data_class' => null,
            //     'constraints' => [
            //         new File([
            //             'maxSize' => '1024k',
            //         ])
            //     ],
            //     'attr' => [
            //         'class' => 'input2',
            //     ]
            // ])
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn haut',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
