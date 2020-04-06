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
            ->add('Title', TextType::class,[
                'label'=>'Titre'
            ])
            ->add('Description', TextareaType::class)
            ->add('StyleId', EntityType::class, [
                'class' => Style::class,
                'choice_label'=>'name',
                'label'=>'Style'
            ])
            ->add('Photos', FileType::class,[
                    'mapped' => false,
                    'multiple'=> true,
                    'required'=>false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
        ]);
    }
}
