<?php

namespace App\Infrastructure\Security;

use App\Domain\Contract\Entity\Contract;
use Symfony\Component\Security\Core\Security;

class DocumentSecurity
{
    private $security;

    private $user;

    public function __construct(Security $security)
    {
        $this->security = $security;
        $this->user = $security->getUser();
    }

    /**
     * @param Contract $contract
     * @param $client_id
     * @return bool
     * Check if client Contrat is the same then client document
     */
    public function contractIsValid(Contract $contract, $client_id) : bool
    {
        if($contract->getClient()->getId() != $client_id) return false;

        return true;
    }
}