<?php

namespace App\Infrastructure\Security;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class ClientAccessService
{
    private $security;

    private $user;

    public function __construct(Security $security)
    {
        $this->security = $security;
        $this->user = $security->getUser();
    }

    public function clientAccess($client)
    {
        if($client->getId() != $this->user->getUuid())
        {
            throw new AccessDeniedException('Vous n\'avez pas accéss à cette page.');
        }
    }
}