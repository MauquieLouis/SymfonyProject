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

class ArticleFormType extends AbstractType
{
    private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function buildForm(FormBuilderInterface $builder,  array $options)
    {
        $builder
            ->add('title', TextType::class, ['help' => 'Put a unique title for this article dussutour !',])
            ->add('content')
            ->add('publishedAt', null,['widget' => 'single_text'])
            ->add('ImageFileName', TextType::class, ['help' => 'Put the image name'])
            ->add('author', EntityType::class, ['class' => User::class,
            'choice_label' => function(User $user){ return sprintf('(%d)==> %s',$user->getId(), $user->getEmail());},
            /*'choices' => $this->userRepository->findAllEmailAlphabetical(),*/
            'placeholder' =>'Choose an Author (schlague) !!',
            /*'invalid_message' =>'Symfony is too smart for your hacking!', 'data_class' => null*/])
            
           /* ->add('author', EntityType::class,['class' => User::class, 'choice_label' => function(User $user)
            { return sprintf('(%d) %s ==> %s',$user->getId(), $user->getFirstName(), $user->getEmail());}, 'placeholder' => 'Choose an Author (schlague) !!'])*/
        ;
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
