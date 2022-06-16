<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReviewFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('createdAt')
            ->add('name', TextType::class, [
                'label' => false, 
                'attr' => [
                    'placeholder' => 'Votre prénom ou pseudo',
                    'class' => 'form-control my-3',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un nom, pseudo ou "anonyme"',
                    ]),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => false, 
                'attr' => [
                    'placeholder' => 'Votre message',
                    'class' => 'form-control my-3',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un message',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Publier votre avis',
                'attr' => [
                    'class' => 'd-block mx-auto my-5 btn btn-outline-primary',
                ]
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
