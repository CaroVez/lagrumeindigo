<?php

namespace App\Form;

use App\Entity\Gym;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GymType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', null, [
                'label' => 'le nouveau gérant créé'
            ])
            ->add('franchise', null, [
                'label' => 'la franchise à laquelle elle appartient'
            ])

            ->add('name', TextType::class, [
                'label' => 'nom',
                'required' => false
            ])
            ->add('address', TextareaType::class, [
                'label' => 'adresse complète',
                'required' => false
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'la salle de sport est-elle active ?',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gym::class,
            'csrf_field_name' => '_token',
        ]);
    }
}
