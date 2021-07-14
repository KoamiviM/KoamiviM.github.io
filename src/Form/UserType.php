<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('plainPassword', RepeatedType::class, array(
              'type' => PasswordType::class,
              'required' => true,
              'first_options' => array('label' => 'Password'),
              'second_options' => array('label' => 'Repeat Password' )
            ))
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'label' => '',
                'choices' => [
                    'Client account' => 'ROLE_USER',
                    'Professional account' => 'ROLE_PRO'
                  ]
            ]);

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
