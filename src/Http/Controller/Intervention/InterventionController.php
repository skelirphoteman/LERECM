<?php

namespace App\Http\Controller\Intervention;

use App\Domain\Client\Entity\Client;
use App\Domain\Intervention\Entity\Intervention;
use App\Http\Form\AddInterventionType;
use App\Infrastructure\Security\AccessService;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


/**
 * Class InterventionController
 * @package App\Http\Controller\Intervention
 * @Route("/app/intervention")
 */
class InterventionController extends AbstractController
{

    private $accessService;

    public function __construct(AccessService $accessService)
    {
        $this->accessService = $accessService;
    }

    /**
     * @Route("/add/{id}", name="app_intervention_add")
     */
    public function addIntervention(Client $id = null,
                            Request $request) : Response
    {
        $client = $id;
        if(!$client){
            throw $this->createNotFoundException('Aucun client trouvé');
        }

        $intervention = new Intervention();
        $intervention->setStartAt(new \DateTimeImmutable('now'));
        $intervention->setEndAt(new \DateTimeImmutable('+ 1 hours'));

        $formIntervention = $this->createForm(AddInterventionType::class, $intervention);

        $this->accessService->companyClientAccess($client);

        return $this->render('app/client/intervention/add.html.twig', [
            'form_intervention' => $formIntervention->createView(),
        ]);
    }
}