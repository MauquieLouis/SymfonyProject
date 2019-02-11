<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Driver\Connection;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * 
 *
 *
 */

class HomeController extends AbstractController
{
    //////////////////////////////////////////////////////////////////////
                                //ACCUEIL//
    //////////////////////////////////////////////////////////////////////
    /**
     * @Route("/home", name="home")
     */
    public function index(EntityManagerInterface $em)
    {
        //dd('slt');
        $repository = $em->getRepository(Article::class);
        $articles = $repository->findAllPublishedOrderedByNewest();
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'articles' => $articles,
        ]);
    }
    //////////////////////////////////////////////////////////////////////
                                 //DYNAMIQUE//
    //////////////////////////////////////////////////////////////////////
    /**
     * @Route("/home/Display/Dynamic", name="home_display_dynamic")
     */
    public function DynDisplay(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Article::class);
        $articles = $repository->findAll();
        
        return $this->render('home/affichage.html.twig', ['articles' => $articles]);
    }
    //////////////////////////////////////////////////////////////////////
                                 //ADMIN//
    //////////////////////////////////////////////////////////////////////
    /**
     * @Route("/admin/home", name="home_admin")
     *  @IsGranted("ROLE_ADMIN")
     */
    public function pageAdmin()
    {
        //$this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('Admin/pageAdmin.html.twig', [
        ]);
    }
    //////////////////////////////////////////////////////////////////////
                                //USER//
    //////////////////////////////////////////////////////////////////////
    /**
     * @Route("/user/home", name="home_user")
     * @IsGranted("ROLE_USER")
     */
    public function pageUser()
    {
        return $this->render('Users/pageUser.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
