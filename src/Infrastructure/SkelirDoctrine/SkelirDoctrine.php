<?php

namespace App\Infrastructure\SkelirDoctrine;

use Doctrine\ORM\EntityManagerInterface;

abstract class SkelirDoctrine
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}