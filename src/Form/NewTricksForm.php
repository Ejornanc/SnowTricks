<?php

namespace App\Form;

use App\Enum\Difficulty;
use App\Enum\TrickType;
use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class NewTricksForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'row_attr' => ['class' => 'new-trick-form-name'],
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'row_attr' => ['class' => 'new-trick-form-description'],
                'attr' => [
                    'rows' => 10,
                    'class' => 'trick-description-textarea',
                    'style' => 'resize: vertical;'
                ],
            ])
            ->add('trickType', EnumType::class, [
                'class' => TrickType::class,
                'label' => false,
                'choice_label' => fn (TrickType $choice) => ucfirst($choice->value),
                'placeholder' => 'Choose a type',
                'row_attr' => ['class' => 'new-trick-form-tricktype new-trick-mobile_form'],
            ])
            ->add('difficulty', EnumType::class, [
                'class' => Difficulty::class,
                'label' => false,
                'choice_label' => fn (Difficulty $choice) => ucfirst($choice->value),
                'placeholder' => 'Choose a difficulty',
                'row_attr' => ['class' => 'new-trick-form-difficulty new-trick-mobile_form'],
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => TrickImageType::class,
                'entry_options' => ['label' => false],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
                'attr' => ['placeholder' => 'Ex: Backflip'],
                'prototype' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
