<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;

class RegistrationGymFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'nom complet',
                'required' => false
            ])
            ->add('email', EmailType::class, [
                'label' => 'courriel',
                'invalid_message' => 'Ce courriel est déjà utilisé',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,
                'first_options'  => array('label' => 'mot de passe'),
                'second_options' => array('label' => 'confirmer le mot de passe'),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit avoir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'téléphone',
                'required' => false
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'rôle',
                'choices' => [
                    'Gérant(e)' => 'ROLE_GYM',            
                ],
            ])
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                fn ($rolesAsArray) => count($rolesAsArray) ? $rolesAsArray[0]: null,
                fn ($rolesAsString) => [$rolesAsString]
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_field_name' => '_token',
        ]);
    }
}
