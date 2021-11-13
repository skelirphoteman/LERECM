<?php

namespace App\Http\Controller\DataExport;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Service
use App\Infrastructure\Security\AccessService;
use App\Infrastructure\DataExport\SkelirCSVExport\SkelirCsvExportInterface;
/**
 * @Route("app/data/export/")
 */
class DataExportClient extends AbstractController
{
    private $accessService;

    public function __construct(AccessService $accessService)
    {
        $this->accessService = $accessService;
    }

    /**
     * @Route("client/list", name="app_export_client_list")
     * @return RedirectResponse
     */
    public function exportDataClient(SkelirCsvExportInterface $exportClientsList)
    {
        $this->accessService->userCompanyActionIsValid();

        return $exportClientsList->generate([
            'company_id' => $this->getUser()->getCompany()->getId(),
        ]);
    }

}