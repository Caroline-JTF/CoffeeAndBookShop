<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Sélectionnez le type d\'évènement' => null,
                    'Dégustation' => 'degustation',
                    'Dédicace' => 'dedicace',
                    'Concert' => 'concert',
                    'Autre' => 'autre',
                ],
                'attr' => [
                    'class' => 'form-control my-3 font-serif',
                ],
            ])
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom de l\'évènement',
                    'class' => 'form-control my-3',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un nom'
                    ])
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Description',
                    'class' => 'form-control my-3',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une description'
                    ])
                ]
            ])
            ->add('date', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une date'
                    ])
                ],
                'attr' => [
                    'class' => 'form-control my-3',
                ],
            ])
            // ->add('participants')
            ->add('place', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nombre de place',
                    'class' => 'form-control my-3',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir un nombre de participants'
                    ])
                ]
            ])
            ->add('img', FileType::class, [
                'label' => false,
                'data_class' => null,
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Les formats autorisés sont .jpg ou .png',
                        'maxSize' => '10M',
                        'maxSizeMessage' => 'Le poids maximal du fichier est : {{ limit }} {{ suffix }} ({{ name }}: {{ size }} {{ suffix }})',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control my-3 font-serif',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'validate' => false,
                'attr' => [
                    'class' => 'btn btn-outline-primary text-white text-primary-hover mb-7 mb-md-0'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'allow_file_upload' => true,
            'img' => null,
        ]);
    }
}
