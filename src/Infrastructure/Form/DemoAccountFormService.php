<?php

namespace App\Infrastructure\Form;

use App\Domain\DemoAccountForm\Entity\DemoAccountForm;
use App\Infrastructure\Security\AccessService;
use Doctrine\ORM\EntityManagerInterface;

class DemoAccountFormService
{
    private $entityManager;

    private $access;

    public function __construct(EntityManagerInterface $entityManager,
                                AccessService $access)
    {
        $this->entityManager = $entityManager;
        $this->access = $access;
    }

    private function insertDemoAccountForm(DemoAccountForm $demoAccountForm)
    {
        $em = $this->entityManager;
        $em->persist($demoAccountForm);
        $em->flush();
    }

    public function addDemoAccountForm(DemoAccountForm $demoAccountForm) : ?String
    {
        $demoAccountForm->setPostAt(new \DateTime('now'));

        $this->insertDemoAccountForm($demoAccountForm);
        return null;
    }
}