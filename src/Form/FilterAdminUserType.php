<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterAdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isActive', ChoiceType::class, [
                'required' => false,
                'label' => '',
                'choices' => [
                    'Activé' => true,
                    'Non activé' => false,
                ]
            ])
            ->add('isLocked', ChoiceType::class, [
                'required' => false,
                'label' => '',
                'choices' => [
                    'Bloqué' => true,
                    'Non bloqué' => false
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'required' => false,
                'label' => '',
                'choices' => [
                    'Compte client' => 'ROLE_USER',
                    'Compte Professionnel' => 'ROLE_PRO',
                    'Compte administrateur' => 'ROLE_ADMIN'
                ]
            ])
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
