<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
                    'class' => 'form-control',
                    'name' => 'title'
            
                ]
            ])
            ->add('image', FileType::class, [
                'data_class' => null,
                'attr' => [ 
                    'label' => 'Please upload a file',
                    'class' => 'form-control',
                    'mapped' => false,
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [ 
                    'placeholder' => 'Enter the movie description',
                    'class' => 'form-control',
                    'name' => 'description'
            
                ]
            ])
            ->add('voteAverage', NumberType::class, [
                'attr' => [ 
                    'placeholder' => 'Enter the movie vote average',
                    'class' => 'form-control',
                    'name' => 'vote_average'
            
                ]
            ])
            ->add('runningTime', NumberType::class, [
                'attr' => [ 
                    'placeholder' => 'Enter the movie running time',
                    'class' => 'form-control',
                    'name' => 'running_time',
                    'type' => 'number',
            
                ]
            ])
            ->add('releaseDate', DateType::class, [
                'attr' => [ 
                    'placeholder' => 'Enter the movie running time',
                    'class' => 'form-control',
                    'name' => 'running_time',
                    'type' => 'number',
            
                ]
            ])
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
