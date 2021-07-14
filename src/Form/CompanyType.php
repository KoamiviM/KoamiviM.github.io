<?php

namespace App\Form;

use App\Entity\Amenity;
use App\Entity\Company;
use App\Repository\AmenityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', null,
                [
                    'attr' =>
                        ['rows' => '10']
                ]
            )
            ->add('amenities', EntityType::class,
                [
                    'class' => Amenity::class,
                    'choice_label' => 'name',
                    'placeholder' => ' Les commodités ',
                    'required' => true,
                    'multiple' => true,
                    'query_builder' => function (AmenityRepository $er)
                        {
                            return $er->createQueryBuilder('a')
                                ->where('a.forcompany = :comp_amenity')
                                ->setParameter('comp_amenity', true);
                        },
                    'attr' => ['class' => 'class_select2']
                ]  )
            ->add('MondayStart', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'class_select2'],
                'placeholder' => 'Ouverture',
                'choices' => $this->getHoursAndMinutes()
            ])
            ->add('MondayEnd', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'class_select2'],
                'placeholder' => 'Fermeture',
                'choices' => $this->getHoursAndMinutes()
            ])
            ->add('TuesdayStart', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'class_select2'],
                'placeholder' => 'Ouverture',
                'choices' => $this->getHoursAndMinutes()
            ])
            ->add('TuesdayEnd', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'class_select2'],
                'placeholder' => 'Fermeture',
                'choices' => $this->getHoursAndMinutes()
            ])
            ->add('WednesdayStart', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'class_select2'],
                'placeholder' => 'Ouverture',
                'choices' => $this->getHoursAndMinutes()
            ])
            ->add('WednesdayEnd', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'class_select2'],
                'placeholder' => 'Fermeture',
                'choices' => $this->getHoursAndMinutes()
            ])
            ->add('ThursdayStart', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'class_select2'],
                'placeholder' => 'Ouverture',
                'choices' => $this->getHoursAndMinutes()
            ])
            ->add('ThursdayEnd', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'class_select2'],
                'placeholder' => 'Fermeture',
                'choices' => $this->getHoursAndMinutes()
            ])
            ->add('FridayStart', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'class_select2'],
                'placeholder' => 'Ouverture',
                'choices' => $this->getHoursAndMinutes()
            ])
            ->add('FridayEnd', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'class_select2'],
                'placeholder' => 'Fermeture',
                'choices' => $this->getHoursAndMinutes()
            ])
            ->add('SaturdayStart', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'class_select2'],
                'placeholder' => 'Ouverture',
                'choices' => $this->getHoursAndMinutes()
            ])
            ->add('SaturdayEnd', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'class_select2'],
                'placeholder' => 'Fermeture',
                'choices' => $this->getHoursAndMinutes()
            ])
            ->add('SundayStart', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'class_select2'],
                'placeholder' => 'Ouverture',
                'choices' => $this->getHoursAndMinutes()
            ])
            ->add('SundayEnd', ChoiceType::class, [
                'required' => true,
                'attr' => ['class' => 'class_select2'],
                'placeholder' => 'Fermeture',
                'choices' => $this->getHoursAndMinutes()
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }

    private function getHoursAndMinutes()
    {
        return [
            '00:00' => '00:00',
            '00:30' => '00:30',
            '01:00' => '01:00',
            '01:30' => '01:30',
            '02:00' => '02:00',
            '02:30' => '02:30',
            '03:00' => '03:00',
            '03:30' => '03:30',
            '04:00' => '04:00',
            '04:30' => '04:30',
            '05:00' => '05:00',
            '05:30' => '05:30',
            '06:00' => '06:00',
            '06:30' => '06:30',
            '07:00' => '07:00',
            '07:30' => '07:30',
            '08:00' => '08:00',
            '08:30' => '08:30',
            '09:00' => '09:00',
            '09:30' => '09:30',
            '10:00' => '10:00',
            '10:30' => '10:30',
            '11:00' => '11:00',
            '11:30' => '11:30',
            '12:00' => '12:00',
            '12:30' => '12:30',
            '13:00' => '13:00',
            '13:30' => '13:30',
            '14:00' => '14:00',
            '14:30' => '14:30',
            '15:00' => '15:00',
            '15:30' => '15:30',
            '16:00' => '16:00',
            '16:30' => '16:30',
            '17:00' => '17:00',
            '17:30' => '17:30',
            '18:00' => '18:00',
            '18:30' => '18:30',
            '19:00' => '19:00',
            '19:30' => '19:30',
            '20:00' => '20:00',
            '20:30' => '20:30',
            '21:00' => '21:00',
            '21:30' => '21:30',
            '22:00' => '22:00',
            '22:30' => '22:30',
            '23:00' => '23:00',
            '23:30' => '23:30',
            'Fermé' => '01:23'
        ];
    }
}
