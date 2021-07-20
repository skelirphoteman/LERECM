<?php


namespace App\Infrastructure\ActionListener;
use App\Domain\ActionListener\Entity\ActionListener;

use Doctrine\ORM\EntityManagerInterface;

abstract class ActionListenerService
{

    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function insertActionListener(ActionListener $actionListener)
    {
        $em = $this->entityManager;
        $em->persist($actionListener);
        $em->flush();
    }
}