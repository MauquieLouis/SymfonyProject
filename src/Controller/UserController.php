<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Driver\Connection;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

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
    public function DynamicDisplay()
    {
        
        return $this->render('Users/dynamicDisplay.html.twig');
    }
}
