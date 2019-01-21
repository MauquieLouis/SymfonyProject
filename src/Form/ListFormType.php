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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class ListFormType extends AbstractType
{
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $entry_options)
    {
                
       /*$builder/*->add('email', EntityType::class,['class' => User::class, 'choice_label' => 'email', 'choice_value' => function (User $entity = null)
            { return $entity ? $entity->getId() : '';}/*function($user){ return $user->getEmail();}/*'choice_label' => 'email'*//*,]) */ //Liste déroulante des emails.
        //->add('Save', SubmitType::class, ['label' => 'Save Changes !'])   //bouton applications modifications.
       
        $builder->add('firstname');
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            
            $user = $event->getData();
            $form = $event->getForm();
            
            //test si l'email existe déja
            if(!$user || null === $user->getId())
            {
                $form->add('email', EntityType::class,['class' => User::class, 'choice_label' => 'email',]);
            }
        });
              
        
        /*$builder->add('email', ChoiceType::class, [
            'choices'  => [
                'Maybe' => null,
                'Yes' => true,
                'No' => false,
            ],
        ])->add('Save', SubmitType::class, ['label' => 'Save Changes !']);*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
