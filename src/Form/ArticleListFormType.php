<?php
namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Repository\ArticleRepository;


class ArticleListFormType extends AbstractType
{
    private $userRepository;
    private $articleRepository;
    
    public function __construct(UserRepository $userRepository, ArticleRepository $articleRepo)
    {
        $this->userRepository = $userRepository;
        $this->articleRepository = $articleRepo;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $entry_options)
    {
        $article = $this->articleRepository->findAll();     
        //grace à cette requete on récupere toutes les lignes de la table article
        //Le tout est stock dans un tableau
        foreach ($article as $art) {            //Boucle for permettant de parcourir le tableau
             $builder                                                                       
             ->add( $art->getSlug(), CheckboxType::class, ['label'    => $art->getTitle(), 'required' => false,]);
             /*
              * On modifie la propriété builder de la classe pour ajouter une checkbox pour chaque
              * article trouve dans la table Article de la BDD
              * 
              * Le nom de chaque checkbox est le slug de l'article en question
              * 
              * Ensuite on met en indiquation de la checkbox (le label) le titre de l'article
              * 
              * 'required' => false car le champs peut être null.
              * 
              */
        }

    }

    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            
        ]);
    }
}
