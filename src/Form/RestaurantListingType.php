<?php

namespace App\Form;

use App\Entity\Amenity;
use App\Entity\Category;
use App\Entity\Company;
use App\Entity\Listing;
use App\Entity\Location;
use App\Entity\Speciality;
use App\Entity\User;
use App\Repository\AmenityRepository;
use App\Repository\CategoryRepository;
use App\Repository\LocationRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\File;

class RestaurantListingType extends AbstractType
{
    private $security;
    private User $current_user;

    public function __construct(Security $security)
    {
        $this->security = $security;
        $this->current_user = $this->security->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company', EntityType::class,
                [
                    'class' => Company::class,
                    'choice_label' => 'name',
                    'placeholder' => ' Choisir l\'entreprise ',
                    'attr' => [
                        'class' => 'class_select2'
                    ]
                ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $r) {
                    return $r->createQueryBuilder('c')
                        ->where('c.parent = :id')
                        ->setParameter('id', 2);
                },
                'choice_label' => 'title',
                'required' => true,
                'placeholder' => ' Choisir la categorie ',
                'attr' => [
                    'class' => 'class_select2'
                ]
            ])
            ->add('specialities', EntityType::class,[
                'class' => Speciality::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir la spécialité',
                'attr' => [
                    'class' => 'class_select2'
                ],
                'required' => true,
                'multiple' => true,
            ])
            ->add('title')
            ->add('description', null,
                [
                    'attr' =>
                        ['rows' => '10']
                ]
            )
            ->add('tags')
            ->add('amenities', EntityType::class,
                [
                    'class' => Amenity::class,
                    'query_builder' => function (AmenityRepository $r) {
                        return $r->createQueryBuilder('a')
                            ->innerJoin('a.category', 'c')
                            ->where('c.parent = :id')
                            ->setParameter('id', 2);
                    },
                    'choice_label' => 'name',
                    'placeholder' => ' Choisir la commodité ',
                    'required' => true,
                    'multiple' => true,
                    'attr' => ['class' => 'class_select2']
                ])
            ->add('price', null, [
                'attr' => [
                    'min' => 1
                ]
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'query_builder' => function (LocationRepository $r) {
                    return $r->createQueryBuilder('l')
                        ->innerJoin('l.owner', 'o')
                        ->where('o.id = :id')
                        ->setParameter('id', $this->current_user->getId());
                },
                'choice_label' => 'name',
                'required' => true,
                'placeholder' => ' Choisir une localisation ',
                'attr' => ['class' => 'class_select2']
            ])
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
                            'mimeTypesMessage' => 'Veuillez choisir une image valide (JPEG ou PNG)',
                        ])
                    ]
                ])
            ->add('imagegroup', ImageGroupType::class)
            ->add('is_active')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Listing::class,
        ]);
    }
}
