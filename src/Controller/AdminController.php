<?php

namespace App\Controller;

use App\Form\ListFormType;
use App\Form\ArticleListFormType;
use App\Form\EditFormType;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    //===========================================CREATE AN ARTICLE000=============================================//
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @Route("/admin/article/new", name="admin_article")
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
           // sprintf("%s-%s",$article->getTitle(), rand(0,1000));   //generation auto du slug
            $article->setSlug(sprintf("%s-%s",$article->getTitle(), rand(0,10000))); 
            //$article->setSlug($buf);                            
            //$article->setAuthor($this->getUser());                      
            $article->setHeartCount(rand(0,100));
            $em->persist($article);        //Pour ajouter � la base de donn�e
            $em->flush();
            
            $this->addFlash('success','Article Created ! you are the boss :) !');
            $this->addFlash('success','Not the big boss like Mr BOYER');
            $this->addFlash('success','BECAUSE "VOUS BOSSEZ PAS LES MECS !!!! "');
            
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
        $form = $this->createForm(ArticleFormType::class, $article);
        
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
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //===========================================SELECTION USER================================================//
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @Route("/admin/selectUser", name="admin_selectUser")
     */
    public function SelectUser(Request $request)
    {
       $form = $this->createForm(ListFormType::class);

       $form->handleRequest($request);  
       if($form->isSubmitted() && $form->isValid())
       {
           return $this->redirectToRoute('admin_accountControl',['id' => 1/*$form->getData(['id'])*/]);
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
     * @Route("/admin/DynamicStream/{id}", name="admin_dynamicStream")
     */
    public function DynamicStream(Article $article, Request $request)
    {
        $form = $this->createForm(ArticleListFormType::class, $article);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
           dd($form->getData());
          // $request_details = $form->get_all_course_requests();
           //$request_details= collect($request_details);
           
            return $this->redirectToRoute('user_displayArticle',['slug' => $form->getData()['slug']]);//$form->getData()['slug']]);//['id' => 1/*$form->getData(['id'])*/]);
        }
        
        return $this->render('Admin/SelectArticle.html.twig',array('form' => $form->createView(),)); 
        //return $this->render('Admin/dynamicStream.html.twig',);
    }
    
}
