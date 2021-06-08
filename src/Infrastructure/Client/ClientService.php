<?php

namespace App\Infrastructure\Client;

use App\Domain\User\Entity\User;
use App\Domain\Client\Entity\Client;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function accessIsValid(User $user) : bool
    {
        if(!$user->getCompany()->getSubscription()->subIsValid()) return false;

        return true;
    }

    private function insertClient(Client $client): void
    {
        $em = $this->entityManager;
        $em->persist($client);
        $em->flush();
    }

    public function addClient(Client $client, User $user) : ?String
    {

        if(!$this->accessIsValid($user)){
            return "Vous ne pouvez pas ajouter de client. Veuillez vérifier que votre abonnement est toujours valide.";
        }

        $this->insertClient($client);

        return null;
    }
}