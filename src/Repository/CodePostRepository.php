<?php


namespace App\Repository;

use App\Entity\CodePost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;


class CodePostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodePost::class);
    }

    public function getAllPostsQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC');
    }


    public function getPostsByUserQuery($user): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->where('c.author = :author')
            ->setParameter('author', $user)
            ->orderBy('c.createdAt', 'DESC');
    }


    public function getPostsByTitleQuery(string $title): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->where('c.title LIKE :title')
            ->setParameter('title', '%' . $title . '%')
            ->orderBy('c.createdAt', 'DESC');
    }
}


