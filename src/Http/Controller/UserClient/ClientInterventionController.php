<?php

namespace App\Http\Controller\UserClient;
use App\Domain\Client\Entity\Client;
use App\Domain\Intervention\Entity\Intervention;
use App\Infrastructure\Security\ClientAccessService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/clientapp/intervention")
 */
class ClientInterventionController extends AbstractController
{
    private $clientAccessService;

    public function __construct(ClientAccessService $clientAccessService)
    {
        $this->clientAccessService = $clientAccessService;
    }

    /**
     * @Route("/list", name="client_intervention_list")
     * @return Response
     */
    public function listClientIntervention() : Response
    {
        $interventionsInComming = $this->getDoctrine()
            ->getRepository(Intervention::class)
            ->findInterventionInCommingForClient($this->getUser()->getUuid());

        $interventionsFinish = $this->getDoctrine()
            ->getRepository(Intervention::class)
            ->findInterventionFinishForClient($this->getUser()->getUuid());

        return $this->render('client/intervention/list.html.twig', [
            'interventions_in_comming' => $interventionsInComming,
            'interventions_finish' => $interventionsFinish,
        ]);
    }
}