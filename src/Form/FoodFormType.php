<?php

namespace App\Form;

use App\Entity\Food;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FoodFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom',
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
                ],
            ])

            ->add('price', TextType::class, [
                'label' => false,
                'help' => '<i>Pour rentrer un chiffre à virgule "," merci d\'utiliser un point "."</i>',
                'help_html' => true,
                'attr' => [
                    'placeholder' => 'Prix',
                    'class' => 'form-control my-3',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un prix'
                    ])
                ],
            ])

            ->add('img', FileType::class, [
                'label' => false,
                'data_class' => null,
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control my-3 font-serif',
                ],
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Les formats autorisés sont .jpg ou .png',
                        'maxSize' => '10M',
                        'maxSizeMessage' => 'Le poids maximal du fichier est : {{ limit }} {{ suffix }} ({{ name }}: {{ size }} {{ suffix }})',
                    ]),
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
            'data_class' => Food::class,
            'allow_file_upload' => true,
            'img' => null,
        ]);
    }
}
