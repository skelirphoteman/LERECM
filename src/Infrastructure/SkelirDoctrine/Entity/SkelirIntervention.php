<?php
namespace App\Infrastructure\SkelirDoctrine\Entity;

use App\Infrastructure\SkelirDoctrine\SkelirDoctrine;
use App\Infrastructure\SkelirDoctrine\SkelirDoctrineInterface;
use App\Domain\Intervention\Entity\Intervention;

class SkelirIntervention extends SkelirDoctrine implements SkelirDoctrineInterface
{
    public function insert($object): bool
    {
        try{
            $this->entityManager->persist($object);
            $this->entityManager->flush();
        }catch (Exception $e) {
            return false;
        }

        return true;
    }
}