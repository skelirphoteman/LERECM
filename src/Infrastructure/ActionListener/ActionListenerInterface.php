<?php


namespace App\Infrastructure\ActionListener;

interface ActionListenerInterface
{
    public function create($object): ?String;

}