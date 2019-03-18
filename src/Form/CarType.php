<?php

namespace App\Form;

use App\Entity\Car;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model', TextType::class, [
              'label_attr' => [
                'class' => 'form_label']
            ])
            ->add('price', NumberType::class)
            ->add('image', ImageType::class, ['label' => false])
            ->add('keywords', CollectionType::class, [
              'entry_type' => KeywordType::class,
              'allow_add' => true,
              'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
