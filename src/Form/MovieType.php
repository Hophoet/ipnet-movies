<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [ 
                    'placeholder' => 'Enter the movie title',
                    'class' => 'sign_input',
                    'name' => 'title',
                ]
            ])
            ->add('image', FileType::class, [
                'attr' => [ 
                    'label' => 'Please upload a file',
                    'class' => 'sign_input',
                    'mapped' => false,
                ]
            ])
            ->add('description')
            ->add('voteAverage')
            ->add('runningTime')
            ->add('releaseDate')
           // ->add('genres', ChoiceType::class, [])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
