<?php

namespace App\Form;

use App\Config\Genres;
use App\Entity\Playlist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class PlaylistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('genre', ChoiceType::class, [
                'choices' => [
                    'Pop' => 'pop',
                    'Rock' => 'rock',
                    'Techno' => 'techno',
                    'Hip Hop' => 'hip_hop',
                    'Grunge' => 'grunge',
                    "90's" => '90_s',
                    "80's" => '80_s',
                    "70's" => '70_s',
                    'Mixed' => 'mixed'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'create',
                'attr' => [
                    'class' => 'btn btn-outline-success',
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }
}
