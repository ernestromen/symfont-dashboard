<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('deleted_at', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'disabled' => true,
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => true,
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
            ])
            ->add('created_at', DateTimeType::class, [
                'widget' => 'single_text',
                'disabled' => true,

                'attr' => [
                    'class' => 'form-control',
                    'readonly' => true,
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
            ])
            ->add('updated_at', DateTimeType::class, [
                'widget' => 'single_text',
                'disabled' => true,

                'attr' => [
                    'class' => 'form-control',
                    'readonly' => true,
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],

            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'id',
                'placeholder' => 'Select a category',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
