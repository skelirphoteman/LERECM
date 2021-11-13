<?php

namespace App\Infrastructure\DataExport\SkelirCSVExport;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

abstract class SkelirCsvExport
{
    protected $content = [];

    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    protected function getResponse(): Response
    {
        $response = new Response($this->content);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="clients_list.csv"');
        return $response;
    }
}