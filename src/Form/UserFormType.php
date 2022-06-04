<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            // ->add('roles')
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom',
                ],
                'constraints' => [
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
                    new Length([
                        'max' => 50,
                        'min' => 2,
                        'maxMessage' => 'Votre nom ne peut dépasser {{ limit }} caractères',
                        'minMessage' => 'Votre nom doit avoir au minimum {{ limit }} caractères',
                    ]),
                ]
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Numéro de téléphone',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email',
                ],
                'constraints' => [
                    new Email([
                        'message' => 'Votre email n\'est pas au bon format: ex. mail@example.com'
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier les informations',
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
