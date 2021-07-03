<?php

namespace App\Infrastructure\Security;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class AccessService
{
    private $security;

    private $user;

    public function __construct(Security $security)
    {
        $this->security = $security;
        $this->user = $security->getUser();
    }

    public function companyClientAccess($client)
    {
        if(!$client->getCompany()->getUsers()->contains($this->user))
        {
            throw new AccessDeniedException('Vous n\'avez pas accéss à cette page.');
        }
    }

    public function subscriptionIsValid()
    {

    }
}