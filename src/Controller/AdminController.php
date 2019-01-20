<?php

namespace App\Controller;

use App\Form\ListFormType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\ArticleFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use App\Repository\ArticleRepository;
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
    /**
     * @Route("/admin/article", name="admin_article")
     */
    public function NewArticle(Request $request, EntityManagerInterface $em)
    {
        //////////////////////////-----ARTICLES NON PUBLIE CAR LA DATE EST NULLE--------////////////////////////
        //$article = new Article();
        $buf ="\0";
        $form = $this->createForm(ArticleFormType::class);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            //dd($form->getData());
            $data = $form->getData();
            $article = new Article();
            $article->setTitle($data['title']);
            $article->setContent($data['content']);
            sprintf($buf,"%s-%s",$article->getTitle(), rand(0,1000));   //generation auto du slug
            $article->setSlug($buf);                            
            $article->setAuthor($this->getUser());                      
            $article->setHeartCount(rand(0,100));
            $em->persist($article);        //Pour ajouter � la base de donn�e
            $em->flush();
            
            return $this->render('Admin/validation.html.twig', ['action' => 'Article Save']);
        }
        /*$form = $this->createFormBuilder($article)     //creation du formulaire
        ->add('publishedAt', DateType::class)
        ->add('title', TextType::class)
        ->add('slug', TextType::class)
        ->add('content', TextareaType::class)
        ->add('author', TextType::class,array('attr' => array('maxlength' =>15))) //Pour un maximum de 15 caract�res
        ->add('Enregistrer', SubmitType::class,  array('label' =>'Save Article'))
        ->getForm();
        
        $form->handleRequest($request);
        
        if (($form->getClickedButton() && 'Enregistrer' === $form->getClickedButton()->getName())) //BOUTON SAUVEGARDER + APERCU
        {
            $blog = $form->getData();
            $this->setData($blog);
            $blog->setHeartCount(rand(5,100))
            ->setImageFilename('informatique.jpg');
            
            $em->persist($blog);        //Pour ajouter � la base de donn�e
            $em->flush();
            $request = 0;
            return $this->render('Admin/validation.html.twig', ['action' => 'Article Save']);
        }*/
        
        return $this->render('Admin/newArticle.html.twig', array('form' => $form->createView(),));
    }
    /**
     * @Route ("/admin/list/article", name ="list_article")
     * 
     */
    public function ListArticle(ArticleRepository $articleRepo)
    {
        $articles = $articleRepo->findAll();
        
        return $this->render("Admin/listArticle.html.twig", ['articles' => $articles,]);
    }
    /**
     * 
     * @Route("/admin/accountControl", name="admin_accountControl")
     */
    public function AccountControl(Request $request)
    {
       $list = new User();
       $form = $this->createForm(ListFormType::class, $list);

       //if (($form->getClickedButton() && 'Save' === $form->getClickedButton()->getName())) //BOUTON SAUVEGARDER + APERCU
       $form->handleRequest($request);
       
       if($form->isSubmitted() && $form->isValid())
       {
           return $this->render('Admin/validation.html.twig',['action' => 'Change save']);
       }
       
       return $this->render('Admin/accountControl.html.twig',array('form' => $form->createView(),)); 
    }
    
    /**
     * @Route("/admin/DynamicStream", name="admin_dynamicStream")
     */
    public function DynamicStream()
    {
        return $this->render('Admin/dynamicStream.html.twig',);
    }
}
