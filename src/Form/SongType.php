<?php

namespace App\Form;

use App\Entity\Playlist;
use App\Entity\Song;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SongType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('band')
            ->add('minDuration', IntegerType::class, [
                'rounding_mode' => \NumberFormatter::ROUND_HALFUP,
                'label' => 'minutes'
            ])
            ->add('secDuration', IntegerType::class, [
                'rounding_mode' => \NumberFormatter::ROUND_HALFUP,
                'label' => 'seconds'
            ])


            ->add('image', FileType::class, [
                'label' => 'Upload a picture for this song',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image in the following fomats: jpeg, png, webp',
                    ])
                ]
            ])
        ;

        if(empty($options['playlist'])) {
            $builder
                ->add('playlists', EntityType::class, [
                    'class' => Playlist::class,
                    'choice_label' => 'id',
                    'multiple' => true,
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Song::class,
            'playlist' => Playlist::class
        ]);
    }
}
