<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut être vide',
                    ]),
                    new Length([
                        'max' => 50,
                        'min' => 2,
                        'maxMessage' => 'Votre nom ne peut dépasser {{ limit }} caractères',
                        'minMessage' => 'Votre nom doit avoir au minimum {{ limit }} caractères',
                    ]),
                ]    
            ])

            ->add('firstname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut être vide',
                    ]),
                    new Length([
                        'max' => 50,
                        'min' => 2,
                        'maxMessage' => 'Votre prénom ne peut dépasser {{ limit }} caractères',
                        'minMessage' => 'Votre prénom doit avoir au minimum {{ limit }} caractères',
                    ]),
                ]
            ])

            ->add('phoneNumber', TelType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Numéro de téléphone',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut être vide',
                    ]),
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Adresse email',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut être vide',
                    ]),
                    new Email([
                        'message' => 'Votre email n\'est pas au bon format: ex. mail@example.com'
                    ]),
                ]

            ])

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Mot de passe',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Ce champ ne peut être vide',
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Répétez votre mot de passe',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Ce champ ne peut être vide',
                        ]),
                    ],
                ],
                'invalid_message' => 'Les mots de passe sont différents !',
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 4096,
                        'minMessage' => 'Votre mot de passe doit avoir au minimum {{ limit }} caractères',
                        'maxMessage' => 'Votre mot de passe ne peut dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
                
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire',
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
            'data_class' => User::class,
        ]);
    }
}
