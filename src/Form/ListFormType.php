<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ListFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //Ajouter les champs pour le formulaire !
        //https://gist.github.com/bpesquet/a27232767712c72406c1
        $builder->add('email', CollectionType::class, [
            // each entry in the array will be an "email" field
            'entry_type' => EmailType::class,
            // these options are passed to each "email" type
            'entry_options' => [
                'attr' => ['class' => 'email-box'],
            ],])
            ->add('email', ChoiceType::class, [
                'choices'  => [
                    'Maybe' => null,
                    'Yes' => true,
                    'No' => false,
                ], ]);
        
            //->add('email')
            //->add('roles')
            //->add('firstName')
            //->add('password')
            //->add('save', SubmitType::class, ['label' => 'Create Post'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
