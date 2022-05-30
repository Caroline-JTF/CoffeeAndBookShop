<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('createdAt')
            ->add('name', TextType::class, [
                'label' => false, 
                'attr' => [
                    'placeholder' => 'Votre nom et prénom',
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => false, 
                'attr' => [
                    'placeholder' => 'Votre message',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Publier votre avis',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
