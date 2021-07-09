<?php

namespace App\Infrastructure\Article;

use App\Domain\Article\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

class ArticleService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function insertArticle(Article $article)
    {
        $em = $this->entityManager;
        $em->persist($article);
        $em->flush();
    }

    public function addArticle(Article $article) : ?String
    {
        $this->insertArticle($article);


        return null;
    }
}