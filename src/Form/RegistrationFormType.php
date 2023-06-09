<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                "attr" => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'E-mail'
                ],
            ])
            ->add('lastname', TextType::class, [
                "attr" => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Last Name'
                ],
            ])
            ->add('firstname', TextType::class, [
                "attr" => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'First Name'
                ],
            ])
            ->add('adress', TextType::class, [
                "attr" => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Adress'
                ]
            ])
            ->add('zipcode', TextType::class, [
                "attr" => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Zip Code'
                ],
            ])
            ->add('city', TextType::class, [
                "attr" => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'City'
                ]
            ])
            ->add('RGPDConsent', CheckboxType::class, [
                'mapped' => false,
                "attr" => [
                    'class' => 'form-check-input mx-2 mt-2 mb-2'
                ],
                "label" => 'I accept the rules',
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}