<?php

namespace App\Infrastructure\Task;

use App\Domain\Task\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function insertTask(Task $task)
    {
        $em = $this->entityManager;
        $em->persist($task);
        $em->flush();
    }

    private function removeTask(Task $task)
    {
        $em = $this->entityManager;
        $em->remove($task);
        $em->flush();
    }


    public function addTask(Task $task) : ?String
    {
        $this->insertTask($task);

        return null;
    }

    public function endTask(Task $task) : ?String
    {
        $task->setState(1);
        $this->insertTask($task);

        return null;
    }

    public function deleteTask(Task $task) : ?String
    {
        $this->removeTask($task);

        return null;
    }
}