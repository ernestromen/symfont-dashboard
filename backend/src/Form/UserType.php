<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('password')
            ->add('deleted_at')
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
            ]);
        if ($options['ROLE_SUPER_ADMIN']) {
            $builder->add('roleEntities', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'name',
                'multiple' => true,
            ]);
        }

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'ROLE_SUPER_ADMIN' => false,
        ]);
        $resolver->setAllowedTypes('ROLE_SUPER_ADMIN', 'bool');
    }
}
