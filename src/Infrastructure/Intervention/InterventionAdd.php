<?php

namespace App\Infrastructure\Intervention;

use App\Domain\Intervention\Entity\Intervention;
use App\Infrastructure\SkelirDoctrine\SkelirDoctrineInterface;

class InterventionAdd implements InterventionInterface
{

    private $skelirIntervention;

    public function __construct(SkelirDoctrineInterface $skelirIntervention)
    {
        $this->skelirIntervention = $skelirIntervention;
    }

    private function dateIsValid(Intervention $intervention)
    {
        return $intervention->getStartAt() < $intervention->getEndAt();
    }


    public function addIntervention(Intervention $intervention, $data = null): ?String
    {
        if(!$this->dateIsValid($intervention))
            return "Les dates de début et de fin sont incohérentes";

        if(!$this->skelirIntervention->insert($intervention))
            return "Un problème est survenu lors de l'enregistrement. Veuillez verifier que tous les éléments sont bons.";


        return null;
    }
}