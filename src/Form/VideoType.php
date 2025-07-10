<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => '<iframe src="..."></iframe>',
                    'rows' => 3,
                    'class' => 'video-url-input',
                ],
                'required' => false,
            ])
            ->add('isModified', \Symfony\Component\Form\Extension\Core\Type\HiddenType::class, [
                'mapped' => false,
                'attr' => ['class' => 'video-is-modified'],
                'data' => '0',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
