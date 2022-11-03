<?php

namespace App\Form;

use App\Entity\Contract;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractFranchiseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('franchise', null, [
                'label' => 'la nouvelle franchise créée'
            ])

            ->add('sendNewsletter', CheckboxType::class, [
                'label' => 'envoyer des bulletins de nouvelles',
                'required' => false
            ])
            ->add('teamPlanning', CheckboxType::class, [
                'label' => 'gestion des horaires d\'équipe',
                'required' => false
            ])
            ->add('sellDrinks', CheckboxType::class, [
                'label' => 'vendre des boissons',
                'required' => false
            ])
            ->add('promotion', CheckboxType::class, [
                'label' => 'promotion de la salle',
                'required' => false
            ])
            ->add('paymentSchedules', CheckboxType::class, [
                'label' => 'calendrier de paiements',
                'required' => false
            ])
            ->add('statistics', CheckboxType::class, [
                'label' => 'statistiques des utilisateurs',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contract::class,
        ]);
    }
}
