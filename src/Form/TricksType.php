<?php

namespace App\Form;

use App\Entity\Style;
use App\Entity\Tricks;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
                'photos', CollectionType::class, [
                    'entry_type'   => PhotoType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'required' => false,
                    'label' => false,
                ]
            )
            ->add(
                'videos', CollectionType::class, [
                    'entry_type'   => VideoType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'required' => false,
                    'label' => false,
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
