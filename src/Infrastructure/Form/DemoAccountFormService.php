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

    private function AntiSpam(DemoAccountForm $demoAccountForm): bool
    {

        $requestExist = $this->entityManager->getRepository(DemoAccountForm::class)
            ->findIfIpAlreadyUser($demoAccountForm->getUserIp());

        if($requestExist)
            return false;
        return true;
    }

    public function addDemoAccountForm(DemoAccountForm $demoAccountForm) : ?String
    {
        if(!$this->AntiSpam($demoAccountForm))
            return "Vous avez déjà fait une demande de compte récemment";
        $demoAccountForm->setPostAt(new \DateTime('now'));

        $this->insertDemoAccountForm($demoAccountForm);
        return null;
    }
}