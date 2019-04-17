<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Choice;
use App\Entity\Tags;
use App\Repository\TagsRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Tests\Fixtures\ToString;

class DynamicFormType extends AbstractType
{
    private $tagsRepository;
    
    public function __construct(TagsRepository $tagsRepository)
    {
        $this->tagsRepository = $tagsRepository;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
           $builder
           ->add('name')
           ->add('location', EntityType::class,[
               'class' => Tags::class,
               'placeholder' => 'Choix',
               'choice_label' => function(Tags $tag){return sprintf('%s',$tag->getCategory());},
               'mapped' => false,
           ])
           ->add('category', ChoiceType::class)
           ;
           $builder->get('location')->addEventListener(
               FormEvents::POST_SUBMIT,
               function(FormEvent $event){
                    $form = $event->getForm();
                    dump($form->getData()->getSubCategory(), $form->getData()->getCategory());
                    $table = $form->getData()->getSubCategory();
//                     $table = $this->tagsRepository->findOneBy(['category' => $form->getData()->getCategory()]);

                    $form->getParent()->add('category', ChoiceType::class,[
//                             'class' => Tags::class,
                            'placeholder' => 'Sous-categorie',
                            'choices' => array_flip(array_values($table)),
                            'mapped' => false,
                            
                        ]);
                   
               });
           
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Choice::class,
        ]);
    }
    
//     private function getLocationNameChoices(string $location)
//     {
//         $planets = [
//             'Mercury',
//             'Venus',
//             'Earth',
//             'Mars',
//             'Jupiter',
//             'Saturn',
//             'Uranus',
//             'Neptune',
//         ];
//         $stars = [
//             'Polaris',
//             'Sirius',
//             'Alpha Centauari A',
//             'Alpha Centauari B',
//             'Betelgeuse',
//             'Rigel',
//             'Other'
//         ];
//         $locationNameChoices = [
//             'solar_system' => array_combine($planets, $planets),
//             'star' => array_combine($stars, $stars),
//             'interstellar_space' => null,
//         ];
//         return $locationNameChoices[$location];
//     }
//     private function setupSpecificLocationNameField(FormInterface $form, ?string $location)
//     {
//         if (null === $location) {
//             $form->remove('category');
//             return;
//         }
//         $choices = $this->getLocationNameChoices($location);
//         if (null === $choices) {
//             $form->remove('category');
//             return;
//         }
//         $form->add('category', ChoiceType::class, [
//             'placeholder' => 'Where exactly?',
//             'choices' => $choices,
//             'required' => false,
//         ]);
// //         dd('form add category');
//     }

}
