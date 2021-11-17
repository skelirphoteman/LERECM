<?php

namespace App\Infrastructure\Intervention;
use App\Domain\Intervention\Entity\Intervention;

Interface InterventionInterface
{

    public function addIntervention(Intervention $intervention, $data = null): ?String;

}