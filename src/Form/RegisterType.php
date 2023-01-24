<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Votre pseudo',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre pseudo',
                    'class' => 'block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40'
                ],
                'label_attr' => [
                    'class' => 'text-main-blue'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre email',
                    'class' => 'block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40'
                ],
                'label_attr' => [
                    'class' => 'text-main-blue'
                ],
                'required' => true,
                'invalid_message' => 'L\'email est obligatoire'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'label' => false,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'required' => true,
                'first_options' => [
                    'label' => 'Votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre mot de passe',
                        'class' => 'block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40'
                    ],
                    'label_attr' => [
                        'class' => 'text-main-blue'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de confirmer votre mot de passe',
                        'class' => 'block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40'
                    ],
                    'label_attr' => [
                        'class' => 'text-main-blue'
                    ]
                ]
            ])

            ->add('avatar', FileType::class, [
                'required' => true,
                'mapped' => false,
                'label' => 'Votre photo de profil',
                'attr' => [
                    'class' => 'form-file border mt-1 rounded py-4 px-4 w-full lg:w-1/2 mx-auto',
                    'placeholder' => 'Importer une image pour votre profil',
                ],
                'constraints' => [
                    new Image([
                        'maxSize' => '2048k',
                        'mimeTypes' => ["image/png", "image/jpeg", "image/pjpeg", "image/svg+xml"],
                        'mimeTypesMessage' => "Formats d'image supportés : .jpg, .png, .jpeg, .svg, .mp4"
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire',
                'attr' => [
                    'class' => 'my-8 w-full py-2 px-4 tracking-wide text-white transition-colors duration-200 transform bg-main-blue rounded-md hover:text-main-blue hover:bg-main-white focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
