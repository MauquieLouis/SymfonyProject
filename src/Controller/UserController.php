<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Driver\Connection;
use App\Entity\Article;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use App\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


/**
 *
 * @IsGranted("ROLE_USER")
 *
 */

class UserController extends AbstractController
{
    /**
     * @Route("/user/DisplayArticle/{slug}", name="user_displayArticle")
     */
    public function DisplayArticle($slug, EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Article::class);
        /** @var article $article */
        $article = $repository->findOneBy(['slug'=> $slug]);
        
        if(!$article)
        {
            throw $this->createNotFoundException(sprintf('No article for slug "%s"', $slug));
        }
        
        return $this->render('Users/displayArticle.html.twig', [
            'controller_name' => 'UserController', 'article' => $article,
        ]);
    }
    /**
     * @Route("/user/Dynamic/Display", name="dynamic_display" )
     */
    public function DynamicDisplay(ArticleRepository $articleRepo)
    {
        $articles = $articleRepo->findAll();
        
        return $this->render('Users/dynamicDisplay.html.twig', ['articles' => $articles]);
    }
    
    /**
     * @Route("/account", name="app_account")
     */
    public function index(LoggerInterface $logger)
    {
        $logger->debug('Checking account page for'.$this->getUser()->getEmail());
        // dd($this->getUser()->getFirstName());
        return $this->render('account/index.html.twig', []);
    }
    
    /**
     * @Route("/account/del/{id}", name="del_account")
     */
    public function DelAccount(User $user,Request $request, EntityManagerInterface $em)
    {
        //dd($user->getId());
        if($user->getId() != $this->getUser()->getId())
        {
            return $this->redirectToRoute('home',);
        }
        
        $form = $this->createFormBuilder()
        ->add('Delete', SubmitType::class, ['label' => 'YES, DELETE My Account', 'attr' => ['class' => 'Btn-delete-Article']])
        ->add('NoDelete', SubmitType::class, ['label' => 'BACK', 'attr' => ['class' => 'Btn-back-listArticle']])
        ->getForm();
        
        //dd($user);
        $form->handleRequest($request);
        
        if (($form->getClickedButton() && 'Delete' === $form->getClickedButton()->getName()))
        {
            
            /*- - - - - - - - - - D E C O N N E C T E R   L ' U T I L I S E U R   A V A N T   D E   L ' E F F A C E R - - - - - - - - - - - */
            //$this->get('security.context')->setToken(null);
            //$this->get('request')->getSession()->invalidate();
            $em->remove($user);        //Pour supprimer un article.
            $em->flush();

            return $this->redirectToRoute('home', );
        }
        if (($form->getClickedButton() && 'NoDelete' === $form->getClickedButton()->getName()))
        {

            return $this->redirectToRoute('app_account',);
        }
        return $this->render('account/delAccount.html.twig', ['form' => $form->createView()]);
    }
}
