<?php

namespace App\Infrastructure\SkelirDoctrine;



Interface SkelirDoctrineInterface
{
    /**
     * Insert Object in database
     * @param $object
     * @return bool
     */
    public function insert($object): bool;
}