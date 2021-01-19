<?php

namespace App\Form\Blog;

use App\Entity\Blog\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,  [
                'label' => 'post.title.label',
                'help'  => 'post.title.help',
                'attr'  => ['placeholder' => 'post.title.placeholder']
            ])
            ->add('digest', TextareaType::class, [
                'label' => 'post.digest.label',
                'help'  => 'post.digest.help',
                'attr'  => ['placeholder' => 'post.digest.placeholder']
            ])
            ->add('content', HiddenType::class)
            ->add('categories');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'         => Post::class,
                'translation_domain' => 'post'
            ]
        );
    }
}
