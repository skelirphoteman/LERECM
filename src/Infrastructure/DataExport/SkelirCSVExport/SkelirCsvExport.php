<?php

namespace App\Infrastructure\DataExport\SkelirCSVExport;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

use App\Infrastructure\Security\AccessService;


abstract class SkelirCsvExport
{
    protected $content = [];

    protected $entityManager;

    protected $accessService;

    public function __construct(EntityManagerInterface $entityManager,
                                AccessService $accessService)
    {
        $this->entityManager = $entityManager;
        $this->accessService = $accessService;
    }


    protected function getResponse(): Response
    {
        $response = new Response($this->content);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="clients_list.csv"');
        return $response;
    }
}