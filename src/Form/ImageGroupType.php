<?php

namespace App\Form;

use App\Entity\ImageGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file1', FileType::class, $this->ImageOptions())
            ->add('file2', FileType::class, $this->ImageOptions())
            ->add('file3', FileType::class, $this->ImageOptions())
            ->add('file4', FileType::class, $this->ImageOptions())
            ->add('file5', FileType::class, $this->ImageOptions())
            ->add('file6', FileType::class, $this->ImageOptions())
            ->add('file7', FileType::class, $this->ImageOptions())
            ->add('file8', FileType::class, $this->ImageOptions())
            ->add('file9', FileType::class, $this->ImageOptions())
        ;
    }

    private function ImageOptions()
    {
        return  [
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
        ];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ImageGroup::class,
        ]);
    }
}
