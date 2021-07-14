<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\DefaultCategory;
use App\Repository\CategoryRepository;
use App\Repository\DefaultCategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parent', EntityType::class, [
                'class' => DefaultCategory::class,
                'choice_label' => 'name',
                'placeholder' => ' Veuillez choisir ',
                'required' => true,
                'multiple' => false,
                'query_builder' => function (DefaultCategoryRepository $er)
                {
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.id != :id')
                        ->setParameter('id', 5);
                },
                'attr' => ['class' => 'class_select2']
            ])
            ->add('title')
            ->add('tags')
            ->add('active')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
