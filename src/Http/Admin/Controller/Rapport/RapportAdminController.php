<?php

namespace App\Http\Admin\Controller\Rapport;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Infrastructure\Rapport\RapportService;
use App\Domain\User\Entity\User;
/**
 * Class RapportAdminController
 * @Route("/admin/rapport")
 */
class RapportAdminController extends AbstractController
{
    /**
     * @Route("/telegram", name="admin_rapport_telegram")
     */
    public function telegramRapport(RapportService $rapportService) : Response
    {
    	$rapportService->generate();

        return $this->redirectToRoute('admin_index');
    }

}