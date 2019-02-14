<?php

namespace App\Controller;

use App\Form\ListFormType;
use App\Form\ArticleListFormType;
use App\Form\EditFormType;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
/**
 *  @IsGranted("ROLE_ADMIN")
 *
 */

class AdminController extends AbstractController
{
    protected $data;
    
    
    public function setData($data)
    {
        $this->data = $data;
    }
    public function getData()
    {
        return $this->data;
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //===========================================CREATE AN ARTICLE=============================================//
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @Route("/admin/article/new", name="admin_article")
     * 
     */
    public function NewArticle(Request $request, EntityManagerInterface $em)
    {
        //////////////////////////-----ARTICLES NON PUBLIE CAR LA DATE EST NULLE--------////////////////////////
        $form = $this->createForm(ArticleFormType::class);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            //dd($form->getData());
          /*  $data = $form->getData();
            $article = new Article();
            $article->setTitle($data['title']);
            $article->setContent($data['content']);*/
            /** @var Article $article */
            $article = $form->getData();
            $article->setAuthor($this->getUser()->getFirstName());                      //L'auteur est le nom de la personne connecté
            $article->setHeartCount(rand(0,100));                                       //Un champ peu utile pour le moment
            $article->setPublishedAt(new \DateTime());                                  //On récupère la date du jour si aucun date n'est renseigné
            $article->setSlug(sprintf("%s-%s",$article->getTitle(), rand(0,10000)));    //Le slug est le nom de l'article qui sera appellé dans l'url 
            
            $em->persist($article);            //Je n'ai malheureusement pas trouvé plus optimisé qu'inclure une première fois l'article pour qu'il obtienne son ID puis changer le nom de l'image avec l'ID de l'article
            $em->flush();
            //dd($article);
            
            $name = $form['title']->getData().$article->getId().'.jpg';
            //dd($form->getData());
            
                
            $form['ImageFileName']->getData()->move(
                ('public/images/')/*.$document->getId()*/,              //.$document->getId()  => à rajouter si on souhaite ajouter un dossier dans public lors de l'enregistrement de l'image
                $name
                );

            
            //$document->setImageFile($fileName);
            $article->setImageFileName($name);
           // sprintf("%s-%s",$article->getTitle(), rand(0,1000));   //generation auto du slug
            $article->setSlug(sprintf("%s-%s",$article->getTitle(), rand(0,10000))); 
            //$article->setSlug($buf);                            
            //$article->setAuthor($this->getUser()); 

            $em->persist($article);        //Pour ajouter � la base de donn�e
            $em->flush();
            
            $this->addFlash('success','Article Created ! you are the boss :) !');

            
            return $this->redirectToRoute('list_article');
        }       
        return $this->render('Admin/newArticle.html.twig', array('form' => $form->createView(),));
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //===========================================EDIT AN ARTICLE===============================================//
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @var int $id
     * @Route ("/admin/article/edit/{id}", name = "admin_article_edit")
     * @IsGranted("ROLE_ADMIN", subject="article")
     */
    public function EditArticle(Article $article, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ArticleFormType::class, $article);//->setData('author',['placeholder' => sprintf('(107)==> %s', $article->getAuthor())]);
        
        
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($article);        //Pour ajouter � la base de donn�e
            $em->flush();
        
            $this->addFlash('success','Article updated');
            return $this->redirectToRoute('admin_article_edit', ['id' => $article->getId()]);

        }  

        return $this->render('Admin/editArticle.html.twig', array('articleForm' => $form->createView(),));
    }

    /**
     * @var int $id
     * @Route ("/admin/article/delete/{id}", name = "admin_article_delete")
     * @IsGranted("ROLE_ADMIN", subject="article")
     */
    public function DeleteArticle(Article $article, Request $request, EntityManagerInterface $em)
    {
        //dd($article);
        $form = $this->createFormBuilder()
        ->add('Delete', SubmitType::class, ['label' => 'YES, Delete this article', 'attr' => ['class' => 'Btn-delete-Article']])
        ->add('NoDelete', SubmitType::class, ['label' => 'BACK', 'attr' => ['class' => 'Btn-back-listArticle']])
        ->getForm();
        
        $form->handleRequest($request);
        
        if (($form->getClickedButton() && 'Delete' === $form->getClickedButton()->getName()))
        {
            $em->remove($article);        //Pour supprimer un article.
            $em->flush();
            
            $this->addFlash('warning','Article delete');
            return $this->redirectToRoute('list_article', );
        }
        if (($form->getClickedButton() && 'NoDelete' === $form->getClickedButton()->getName()))
        {

            return $this->redirectToRoute('list_article',);
        }
        return $this->render('Admin/validation.html.twig', array('action' => $form->createView(),));
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //===========================================SELECTION USER================================================//
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @Route("/admin/selectUser", name="admin_selectUser")
     */
    public function SelectUser(Request $request, UserRepository $uR,  EntityManagerInterface $em)
    {
       $form = $this->createForm(ListFormType::class);

       $form->handleRequest($request);  
       if($form->isSubmitted() && $form->isValid())
       {
           //dd($form->getData()['Role']);
           //dd($form->getData()['user']->getId());
           $user = $uR->findOneBy(['id' =>$form->getData()['user']->getId()]);
           //dd($user);
           switch($form->getData()['Role'])
           {
               case 'ROLE_USER':
                   //dd("CASE ===> ROLE_USER");
                   $user->setRoles([]);
                   break;
                   
               case 'ROLE_ADMIN':
                   //dd( $form->getData()['Role']);
                   $user->setRoles([$form->getData()['Role']]);
                   break;
                   
               case 'DELETE':
                   dd("CASE ===> DELETE");
                   break;
                   
               default:
                   dd("ERROR =====> \'form->getData()[\'Role\']\' ");
                   break;
           }
           $em->persist($user);        //Pour ajouter � la base de donn�e
           $em->flush();
           
           return $this->redirectToRoute('list_article');
       }
       
       return $this->render('Admin/selectUser.html.twig',array('form' => $form->createView(),)); 
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //===========================================ACCOUNT CONTROL===============================================//
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @Route("/admin/accountControl/{id}", name="admin_accountControl")
     */
    public function AccountControl(User $user, Request $request, EntityManagerInterface $em)
    {
        //dd($user);
        $form = $this->createForm(EditFormType::class, $user);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            //dd($data);
            dd($data);
            $user->setRoles([$form->getData()['roles']]);
            $em->persist($user);        //Pour ajouter � la base de donn�e
            $em->flush();
            
            return $this->redirectToRoute('home');//,['id' => $data->getId()]);
        }
        return $this->render('Admin/accountControl.html.twig',array('form' => $form->createView(),)); 
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //===========================================LIST ARTICLE==================================================//
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    /**
     * @Route ("/admin/list/article", name ="list_article")
     *
     */
    public function ListArticle(ArticleRepository $articleRepo)
    {
        $articles = $articleRepo->findAll();
        
        return $this->render("Admin/listArticle.html.twig", ['articles' => $articles,]);
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //===========================================CONFIGURE DYNAMIC=============================================//
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @Route("/admin/DynamicStream/", name="admin_dynamicStream")
     */
    public function DynamicStream(Request $request, ArticleRepository $articleRepo, EntityManagerInterface $em)
    {
        
        $articles = $articleRepo->findAll();
        $form= $this->createForm(ArticleListFormType::class,);
         
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())    //Si le form est submit
        {

           foreach($articles as $art)                   //On parcours les articles
           {
                //-----PARCOUR DES ARTICLES DE LA BDD------//
                
               $art->setChecked($form->getData()[$art->getId()]); 
               //------------------EXPLICATION DE LA LIGNE AU DESSUS---------------//
               /*
                * 1°.  Aller voir le formulaire ArticleListFormType.php
                * 
                * Pour l'article en question on modifie la propriée checked (de la BDD (Base de Donne))
                * 
                * On lui passe en paramètre le retour de la valeur de la checkbox avec le même ID que l'article
                * 
                */
               $em->persist($art);        //Pour ajouter à la base de donnee
               $em->flush();
           }
           
           return $this->redirectToRoute('home_admin',);
        }
        return $this->render('Admin/SelectArticle.html.twig',
            array('form' => $form->createView(),
                'articles' => $articles,
                )); 
        //return $this->render('Admin/dynamicStream.html.twig',);
    }
    
}
