<?php

namespace App\Form;

use App\Entity\Blog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Le nom de votre blog',
                'attr' => [
                    'placeholder' => 'Merci de saisir le nom de votre blog',
                    'class' => 'form-input block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du blog',
                'attr' => [
                    'placeholder' => 'Saisir la description de ce blog',
                    'class' => 'form-input block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40'
                ]
            ])
            ->add('illustration', FileType::class, [
                'required' => true,
                'mapped' => false,
                'label' => 'Image illustrant le blog',
                'attr' => [
                    'class' => 'form-file border mt-1 rounded py-4 px-4 w-full lg:w-1/2 mx-auto',
                    'placeholder' => 'Importer une image pour ce blog',
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
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'my-8 w-full px-4 py-2 tracking-wide text-white transition-colors duration-200 transform bg-main-blue rounded-md hover:text-main-blue hover:bg-main-light-blue focus:outline-none focus:bg-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-50'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
