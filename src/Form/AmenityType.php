<?php

namespace App\Form;

use App\Entity\Amenity;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AmenityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('category', EntityType::class,
            [
                'class' => Category::class,
                'choice_label' => 'title',
                'query_builder' => function(CategoryRepository $categoryRepository){
                    $qb = $categoryRepository->createQueryBuilder('category')
                        ->innerJoin('category.parent', 'parent');
                    return $qb;
                },
                'group_by' => function(Category $category) {
                    return $category->getParent()->getName();
                },
                'placeholder' => ' Veuillez choisir ',
                'required' => true,
                'multiple' => false,
                'attr' => ['class' => 'class_select2']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Amenity::class,
        ]);
    }
}
