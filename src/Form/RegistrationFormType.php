<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\File;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null ,['label'=> false])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => false,
                ],
                'second_options' => [
                    'label' => false,
                ],
                'invalid_message' => 'The password fields must match.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
            ->add('username', TextType::class, ['label'=> false], [
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your username should be at least {{ limit }} characters',
                        'max' => 20,
                        'maxMessage' => 'Your username should be at most {{ limit }} characters',
                    ]),
                    new NotBlank([
                        'message' => 'Please enter an Username',
                    ]),
                ],
            ])
            ->add('avatar', FileType::class, ['mapped'=> false], [
                'constraints' => [
                    new File([
                        'maxSize' => '8192k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid png/jpg/webp image',
                    ])
                ] 
            ])
            ->add('history', TextareaType::class, ['label'=> false], [
                'constraints' => [
                    new Length([
                        'min' => 50,
                        'minMessage' => 'Your history should be at least {{ limit }} characters',
                    ]),
                    new NotBlank([
                        'message' => 'Please enter an history',
                    ]),
                ],
            ])
            ->add('intelligency', IntegerType::class,['label'=> false],  [
                'constraints' => [
                    new Range([
                        'min' => 10,
                        'minMessage' => 'Your intelligency should be at least {{ limit }} ',
                        'max' => 75,
                        'maxMessage' => 'Your intelligency should be at most {{ limit }} ',
                    ]),
                    new NotBlank([
                        'message' => 'Please enter an Username',
                    ]),
                    new Positive([
                        'message' => 'Please enter a number positive',
                    ]),
                ],
            ])
            ->add('power', IntegerType::class, ['label'=> false], [
                'constraints' => [
                    new Range([
                        'min' => 10,
                        'minMessage' => 'Your power should be at least {{ limit }} ',
                        'max' => 75,
                        'maxMessage' => 'Your power should be at most {{ limit }} ',
                    ]),
                    new NotBlank([
                        'message' => 'Please enter an Username',
                    ]),
                    new Positive([
                        'message' => 'Please enter a number positive',
                    ]),
                ],
            ])
            ->add('social', IntegerType::class, ['label'=> false], [
                'constraints' => [
                    new Range([
                        'min' => 10,
                        'minMessage' => 'Your social should be at least {{ limit }} ',
                        'max' => 75,
                        'maxMessage' => 'Your social should be at most {{ limit }} ',
                    ]),
                    new NotBlank([
                        'message' => 'Please enter an Username',
                    ]),
                    new Positive([
                        'message' => 'Please enter a number positive',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
} 
