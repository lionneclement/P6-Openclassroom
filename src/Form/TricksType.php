<?php

namespace App\Form;

use App\Entity\Style;
use App\Entity\Tricks;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TricksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title', TextType::class, [
                'label'=>'Titre'
                ]
            )
            ->add('description', TextareaType::class)
            ->add(
                'styleId', EntityType::class, [
                'class' => Style::class,
                'choice_label'=>'name',
                'label'=>'Style'
                ]
            )
            ->add(
                'photos', FileType::class, [
                    'mapped' => false,
                    'multiple'=> true,
                    'required'=>false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => Tricks::class,
            ]
        );
    }
}
