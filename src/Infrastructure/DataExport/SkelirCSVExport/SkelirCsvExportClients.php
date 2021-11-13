<?php

namespace App\Infrastructure\DataExport\SkelirCSVExport;


use Symfony\Component\HttpFoundation\Response;


//domaib
use App\Domain\Client\Entity\Client;
class SkelirCsvExportClients extends SkelirCsvExport implements SkelirCsvExportInterface
{
    public function generate(array $data) : Response
    {
        $clients = $this->entityManager->getRepository(Client::class)
            ->getClientsListToExport($data['company_id']);

        $rows = array();
        $firstRow = array(
            'ID',
            'Prénom',
            'Nom',
            'Num. Tel.',
            'Num. Tel. Maison',
            'Email',
            'Adresse',
            'Ville',
            'Code Postal',
            'Pays',
            'Nom de l\'entreprise',
            'Num. Siret',
            'Code APE',
            'Num. TVA',
            'Note',
            'Date d\'anniversaire',
            'Créer le '
        );

        $rows[] = implode(',', $firstRow);

        foreach($clients as $client)
        {
            $data = array($client->getId(),
                $client->getName(),
                $client->getSurname(),
                $client->getPhone(),
                $client->getHomePhone(),
                $client->getEmail(),
                $client->getAvenue(),
                $client->getCity(),
                $client->getPostalCode(),
                $client->getCountry(),
                $client->getCompanyName(),
                $client->getSiret(),
                $client->getApeCode(),
                $client->getTvaCode(),
                $client->getNote(),
                $client->getBirthdayString(),
                $client->getCreatedAtString(),);

            $rows[] = implode(',', $data);
        }

        $this->content = implode("\n", $rows);

        return $this->getResponse();
    }

}