<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// FORM TYPES
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserLoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, [
                "label" => false,
                "attr" => [
                    "placeholder" => "Nom d'utilisateur",
                    "autocomplete" => "off"
                ]
            ])
            ->add('password', PasswordType::class, [
                "label" => false,
                "attr" => [
                    "placeholder" => "Mot de passe",
                    "autocomplete" => "off",
                    "class" => "passwordfield"
                ]
            ])
            ->add("connexion", SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}