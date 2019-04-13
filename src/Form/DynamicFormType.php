<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Choice;
use App\Repository\TagsRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;

class DynamicFormType extends AbstractType
{
    private $tagsRepository;
    
    public function __construct(TagsRepository $tagsRepository)
    {
        $this->tagsRepository = $tagsRepository;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choice = $options['data'] ?? null;
        $isEdit = $choice && $choice->getId();
        $location = $choice ? $choice->getLocation() : null;
        
        $builder
            ->add('name', TextType::class)
            ->add('location', ChoiceType::class,[
                'choices' =>
                [
                    'The Solar System' => 'solar_system',
                    'Near a star' => 'star',
                    'Interstellar Space' => 'interstellar_space'
                ],
                'required' => false,
            ])
            ;
            if($location)
            { 
                $builder
                ->add('category', ChoiceType::class, [
                    'placeholder' => 'Where exactly?',
                    'choices' => [
                        'choices' => $this->getLocationNameChoices($location),
                    ],
                    'required' => false,
                ])
                ;
            }
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function(FormEvent $event)
                {
                    $data = $event->getData();
                    if(!$data)
                    {
                        return;
                    }
//                     dd($event);
                    $this->setupSpecificLocationNameField(
                        $event->getForm(),
                        $data->getLocation()
                        );
                }
            );
            $builder->get('location')->addEventListener(
                FormEvents::POST_SUBMIT,
                function(FormEvent $event) {
                    $form = $event->getForm();
                    $this->setupSpecificLocationNameField(
                        $form->getParent(),
                        $form->getData()
                        );
            });
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Choice::class,
        ]);
    }
    
    private function getLocationNameChoices(string $location)
    {
        $planets = [
            'Mercury',
            'Venus',
            'Earth',
            'Mars',
            'Jupiter',
            'Saturn',
            'Uranus',
            'Neptune',
        ];
        $stars = [
            'Polaris',
            'Sirius',
            'Alpha Centauari A',
            'Alpha Centauari B',
            'Betelgeuse',
            'Rigel',
            'Other'
        ];
        $locationNameChoices = [
            'solar_system' => array_combine($planets, $planets),
            'star' => array_combine($stars, $stars),
            'interstellar_space' => null,
        ];
        return $locationNameChoices[$location];
    }
    private function setupSpecificLocationNameField(FormInterface $form, ?string $location)
    {
        if (null === $location) {
            $form->remove('category');
            return;
        }
        $choices = $this->getLocationNameChoices($location);
        if (null === $choices) {
            $form->remove('category');
            return;
        }
        $form->add('category', ChoiceType::class, [
            'placeholder' => 'Where exactly?',
            'choices' => $choices,
            'required' => false,
        ]);
//         dd('form add category');
    }

}
