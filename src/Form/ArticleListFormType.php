<?php
namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class ArticleListFormType extends AbstractType
{
    private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $entry_options)
    {
                
       $builder
       ->add('title', EntityType::class, ['class' => Article::class,
           'choice_label' => function(Article $article){ return  $article->getTitle();},
           'placeholder' =>'Choose The Article To Display', ]);
       
    }

    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            
        ]);
    }
}
