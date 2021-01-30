<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Range;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
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
                    'label' => 'Password',
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                ],
                'invalid_message' => 'The password fields must match.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
            ->add('username', TextType::class,  [
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
            ->add('avatar')
            ->add('history', TextType::class,  [
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
            ->add('intelligency', IntegerType::class,  [
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
            ->add('power', IntegerType::class,  [
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
            ->add('social', IntegerType::class,  [
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
