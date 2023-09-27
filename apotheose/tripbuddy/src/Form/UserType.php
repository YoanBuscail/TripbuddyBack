<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
    $builder
        ->add('email', EmailType::class, [
            "label" => "Email de l'utilisateur",
            "attr" => [
                "placeholder" => "example@oclock.io"
            ]
        ])
        ->add('firstname')
        ->add('lastname')
        ->add('roles', ChoiceType::class, [
            "choices" => [
                "Administrateur" => "ROLE_ADMIN",
                "Utilisateur" => "ROLE_USER"
            ],
            "multiple" => true,
            "expanded" => true,
            "label" => "Rôles"
        ]);
        // j'utilise la custom_option pour faire un affichage conditionnel sur le mot de passe 
        if($options["custom_option"] !== "edit"){
            $builder
            ->add('password',RepeatedType::class,[
                "type" => PasswordType::class,
                "first_options" => ["label" => "Saisissez un mot de passe","help" => "Le mot de passe doit avoir minimum 4 caractères"],
                "second_options" => ["label" => "Confirmez le mot de passe"],
                "invalid_message" => "Les champs doivent être identiques"
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            "custom_option" => "default"
        ]);
    }
}
