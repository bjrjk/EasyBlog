<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getArticlesByRange(int $start, int $num): array
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.date', 'DESC')
            ->setFirstResult($start)
            ->setMaxResults($num)
            ;
        return $qb->getQuery()->execute();
    }

    public function getArticleByID(int $id): ?Article
    {
        return $this->find($id);
    }

    public function updateArticleByID(
        int $id,
        string $title,
        string $content
    )
    {
        $article = $this->find($id);
        $article->setTitle($title);
        $article->setContent($content);
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
    }

    public function createArticle(
        string $title,
        string $content
    ):int
    {
        $article = new Article();
        $article->setTitle($title);
        $article->setDate(new \DateTime());
        $article->setContent($content);
        $entityManager = $this->getEntityManager();
        $entityManager->persist($article);
        $entityManager->flush();
        return $article->getId();
    }

    public function deleteArticle(int $id)
    {
        $article = $this->find($id);
        $entityManager = $this->getEntityManager();
        $entityManager->remove($article);
        $entityManager->flush();
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
