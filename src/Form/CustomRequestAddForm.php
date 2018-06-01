<?php

namespace App\Form;


use App\Entity\CustomRequest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomRequestAddForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('title', TextType::class, ['label' => 'Titre', 'attr' => array('rows'=>'4', 'cols'=>'50')])
            ->add('description', TextareaType::class)
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => 'App\Entity\Category',
                'choice_label' => 'name',
                'placeholder' => '-- Choisissez une catégorie --',
                'expanded' => false,
                'multiple' => false
            ])
            ->add('photoPath')
            ->add('Valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CustomRequest::class,

        ]);

    }


    public function getBlockPrefix()
    {
        return 'my_form_movie';
    }
}