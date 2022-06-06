<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'évènement : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un nom'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une description'
                    ])
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type d\évènement :',
                'choices' => [
                    'Sélectionnez un type d\'évènement' => null,
                    'Dégustation' => 'degustation',
                    'Dédicace' => 'dedicace',
                    'Autre' => 'autre',
                ]
            ])
            ->add('date', DateType::class, [
                'label' => 'Date : ',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une date'
                    ])
                ]
            ])
            ->add('place', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nombre de place',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir un nombre de participants'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'validate' => false,
                'attr' => [
                    'class' => 'btn-center'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
