<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EditFormType extends AbstractType
{
    private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('roles', ChoiceType::class,['placeholder' => 'Choose a Role', 'choices' => ['Admin' => 'ROLE_ADMIN', 'User' => 'ROLE_USER']]);
           /* ->add('email', EntityType::class, ['class' => User::class,
                'choice_label' => function(User $user){ return sprintf('(%d)==> %s',$user->getId(), $user->getEmail());},
                /*'choices' => $this->userRepository->findAllEmailAlphabetical(),
            'placeholder' =>'Choose an Author (schlague) !!',
            /*'invalid_message' =>'Symfony is too smart for your hacking!', 'data_class' => null])*/
            //->add('roles'); /*ChoiceType::class,);// ['choices' => ['Admin' => 'ROLE_ADMIN', 'User' => 'ROLE_USER'], 'placeholder' => 'pick a role'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
