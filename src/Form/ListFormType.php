<?php
namespace App\Form;

use App\Entity\User;
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


class ListFormType extends AbstractType
{
    private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $entry_options)
    {
                
       $builder
       ->add('user', EntityType::class, ['class' => User::class,
           'choice_label' => function(User $user){ return sprintf('%s',$user->getEmail());},
           'placeholder' =>'Choose  a User',])
       ->add('Role', ChoiceType::class, [
               'choices'  => [
                   'Give Admin permission' => 'ROLE_ADMIN',
                   'Give Only User permission' => 'ROLE_USER',
                   'Delete Account' => 'DELETE',
               ],'placeholder' =>'Choose  an Action',]);
       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            
        ]);
    }
}
