<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\Location;
use App\Repository\CityRepository;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('phone1')
            ->add('phone2')
            ->add('is_phone1_wzp')
            ->add('is_phone2_wzp')
            ->add('website')
            ->add('youtube_link')
            ->add('facebook_link')
            ->add('instagram_link')
            ->add('city', EntityType::class,
                [
                    'class' => City::class,
                    'choice_label' => 'name',
                    'query_builder' => function(CityRepository $cityRepository){
                        $qb = $cityRepository->createQueryBuilder('city')
                            ->innerJoin('city.country', 'country');
                        return $qb;
                    },
                    'group_by' => function(City $city) {
                        return $city->getCountry()->getName();
                    },
                    'placeholder' => ' Select a city ',
                    'required' => true,
                    'multiple' => false,
                    'attr' => ['class' => 'class_select2']
                ])
            ->add('latitude', NumberType::class, ['attr' => ['readonly' => 'readonly']])
            ->add('longitude', NumberType::class, ['attr' => ['readonly' => 'readonly']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
