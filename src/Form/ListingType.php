<?php

namespace App\Form;

use App\Entity\Amenity;
use App\Entity\Category;
use App\Entity\Listing;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description', null,
                [
                    'attr' =>
                        ['rows' => '10']
                ]
            )
            ->add('tags')
            ->add('is_active')
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'name',
                'required' => true,
                'placeholder' => ' Select a location ',
                'attr' => ['class' => 'class_select2']
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'required' => true,
                'placeholder' => ' Select a category ',
                'attr' => [
                    'class' => 'class_select2'
                ]
            ])
            ->add('amenities', EntityType::class,
                [
                    'class' => Amenity::class,
                    'choice_label' => 'name',
                    'placeholder' => ' Select amenities ',
                    'required' => true,
                    'multiple' => true,
                    'attr' => ['class' => 'class_select2']
                ]  )
            ->add('imageFile', FileType::class,
                [
                    'required' => false,
                    'attr' => [
                        'onchange' => 'readUploadedFile(this)',
                    ],
                    'constraints' => [
                        new File([
                            'maxSize' => '4096k',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid image (JPEG or PNG)',
                        ])
                    ]
                ])
            ->add('images', CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'required' => false,
                    'label'=>false,
                    'by_reference' => false,
                    'disabled' => false
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Listing::class,
        ]);
    }
}
