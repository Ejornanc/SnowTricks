<?php

namespace App\Form;

use App\Enum\Difficulty;
use App\Enum\TrickType;
use App\Entity\Trick;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class NewTricksForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'row_attr' => ['class' => 'new-trick-form-name'],
            ])
            ->add('description', TextType::class, [
                'row_attr' => ['class' => 'new-trick-form-description'],
            ])
            ->add('Trick_Type', EnumType::class, [
                'class' => TrickType::class,
                'choice_label' => fn (TrickType $choice) => ucfirst($choice->value),
                'placeholder' => 'Choose a type',
                'row_attr' => ['class' => 'new-trick-form-tricktype'],
            ])
            ->add('difficulty', EnumType::class, [
                'class' => Difficulty::class,
                'choice_label' => fn (Difficulty $choice) => ucfirst($choice->value),
                'placeholder' => 'Choose a difficulty',
                'row_attr' => ['class' => 'new-trick-form-difficulty'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
