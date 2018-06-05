<?php

namespace App\Form;

use App\Form\MessagingForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

//Ne sert à rien pour l'instant mais peut être utile dans le cas où 
//on voudrait utiliser le formulaire dans plusieurs twigs.

class MessagingForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('_sender', HiddenType::class)
        ->add('_message', TextareaType::class)
        ->add('submit', SubmitType::class)
    ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
