<?php

namespace App\Form;

use App\Entity\Contract;
use App\Repository\GymRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractGymType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gym', null, [
                'label' => 'la nouvelle salle de sport créée',
                'query_builder' => function(GymRepository $repository) { 
                    return $repository->createQueryBuilder('g')->orderBy('g.id', 'DESC' );
                }
            ])

            ->add('sendNewsletter', CheckboxType::class, [
                'label' => 'envoyer des bulletins de nouvelles',
                'required' => false
                //'disabled' => true
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
            'csrf_field_name' => '_token',
        ]);
    }
}
