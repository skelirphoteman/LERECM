<?php

namespace App\Infrastructure\DataExport\SkelirCSVExport;


use Symfony\Component\HttpFoundation\Response;

//service
use App\Infrastructure\Security\AccessService;
//domaib
use App\Domain\Client\Entity\Client;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\NotFoundHttpException;

class SkelirCsvExportClientProfil extends SkelirCsvExport implements SkelirCsvExportInterface
{

    public function generate(array $data) : Response
    {
        $client = $this->entityManager->getRepository(Client::class)
            ->getClientProfilToExport($data['client_id']);

        if(empty($client))
            throw new NotFoundHttpException("Auncun client trouvé.");

        $this->accessService->companyClientAccess($client);

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

        $this->content = implode("\n", $rows);

        return $this->getResponse();
    }


}