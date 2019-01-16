<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }
    
    //POUR CREER DES REQUETES PERSONNALISE
    public function findAllPublishedOrderedByNewest()
    {
        return $this->addIsPublishedQueryBuilder()
        ->orderBy('a.publishedAt', 'DESC')
        ->getQuery()
        ->getResult();
        
    }
    
    private function addIsPublishedQueryBuilder(QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuilder($qb)->andWhere('a.publishedAt IS NOT NULL');
    }
    
    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ? : $this->createQueryBuilder('a');
    }
    
    // /**
    //  * @return Blog[] Returns an array of Blog objects
    //  */
    /*
     public function findByExampleField($value)
     {
     return $this->createQueryBuilder('b')
     ->andWhere('b.exampleField = :val')
     ->setParameter('val', $value)
     ->orderBy('b.id', 'ASC')
     ->setMaxResults(10)
     ->getQuery()
     ->getResult()
     ;
     }
     */
    
    
    /*
     public function findOneBySomeField($value): ?Blog
     {
     return $this->createQueryBuilder('b')
     ->andWhere('b.exampleField = :val')
     ->setParameter('val', $value)
     ->getQuery()
     ->getOneOrNullResult()
     ;
     }
     */
}