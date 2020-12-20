<?php

namespace App\Form\Blog;

use App\Entity\Blog\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'category.name.label',
                'help'  => 'category.name.help',
                'attr'  => ['placeholder' => 'category.name.placeholder']
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'category.enabled.label',
                'help'  => 'category.enabled.help',
            ])
            ->add('description', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'         => Category::class,
                'translation_domain' => 'category'
            ]
        );
    }
}
