<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Blog;
use App\Repository\BlogRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('title', TextType::class, [
                'label' => 'Le titre de votre article',
                'attr' => [
                    'placeholder' => 'Merci de saisir le titre de votre article',
                    'class' => 'form-input block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40'
                ],
                'label_attr' => [
                    'class' => 'text-main-blue text-lg lg:text-xl'
                ]
            ])
            ->add('introduction', TextareaType::class, [
                'label' => 'Introduction de l\'article',
                'attr' => [
                    'placeholder' => 'Saisir l\'introduction de cet article',
                    'class' => 'form-input block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40'
                ],
                'label_attr' => [
                    'class' => 'text-main-blue text-lg lg:text-xl'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu de l\'article',
                'attr' => [
                    'placeholder' => 'Saisir le contenu de cet article',
                    'class' => 'form-input block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40'
                ],
                'label_attr' => [
                    'class' => 'text-main-blue text-lg lg:text-xl'
                ]
            ])
            ->add('blog', EntityType::class, [
                'label' => 'Choix du blog',
                'label_attr' => [
                    'class' => 'text-main-blue text-lg lg:text-xl'
                ],
                'required' => true,
                'class' => Blog::class,
                'query_builder' => function (BlogRepository $br) use ($user) {
                    return $br->createQueryBuilder('b')
                        ->andWhere('b.author = :user_id')
                        ->setParameter('user_id', $user->getId())
                        ->orderBy('b.id', 'ASC');
                },
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'bg-main-white px-4 flex items-center space-x-2'
                ],
                'choice_attr' => [
                    'class' => 'text-main-white ml-5'
                ],
                'row_attr' => [
                    'class' => 'text-main-white mb-5'
                ]
            ])
            ->add('images', FileType::class,[
                'label' => 'Importer des images pour cet article',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'label_attr' => [
                    'class' => 'text-main-blue text-lg lg:text-xl mb-5'
                ],
                'attr' => [
                    'placeholder' => 'Importer les images de l\'article',
                    'class' => 'py-4 border text-main-white mt-1 rounded px-4 w-full bg-gray-50'
                ],
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
            'data_class' => Articles::class,
            'user' => null
        ]);
    }
}
