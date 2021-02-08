<?php

namespace App\Form\User;

use App\Entity\User\User;
use App\Security\Roles\RoleUtils;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    private RoleUtils $roleUtils;

    public function __construct(RoleUtils $roleUtils)
    {
        $this->roleUtils = $roleUtils;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'user.email.label',
                'help'  => 'user.email.help',
                'attr'  => ['placeholder' => 'user.email.placeholder']
            ])
            ->add('roles', ChoiceType::class, [
                'label'    => 'user.roles.label',
                'help'     => 'user.roles.help',
                'choices'  => $this->roleUtils->getChoicesToForm(),
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'            => PasswordType::class,
                'invalid_message' => 'user.password.repeat_error',
                'options'         => ['attr' => ['class' => 'password-field']],
                'required'        => false,
                'first_options'   => ['label' => 'user.password.label'],
                'second_options'  => [
                    'label' => 'user.password.repeat_label',
                    'attr'  => ['class' => 'mb-3']
                ],
                'help'            => 'user.password.help',
            ])
            ->add('firstname', TextType::class, [
                'label' => 'user.firstname.label',
                'attr'  => ['placeholder' => 'user.firstname.placeholder']
            ])
            ->add('lastname', TextType::class, [
                'label' => 'user.lastname.label',
                'attr'  => ['placeholder' => 'user.lastname.placeholder']
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'user.enabled.label',
                'help'  => 'user.enabled.help',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'         => User::class,
                'translation_domain' => 'user',
            ]
        );
    }
}
