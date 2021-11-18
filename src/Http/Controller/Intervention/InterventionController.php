<?php

namespace App\Http\Controller\Intervention;

use App\Domain\Client\Entity\Client;
use App\Domain\Intervention\Entity\Intervention;
use App\Http\Form\AddInterventionType;
use App\Infrastructure\Security\AccessService;
use App\Infrastructure\Intervention\InterventionInterface;

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
                            Request $request,
                            InterventionInterface $createIntervention) : Response
    {
        $client = $id;
        if(!$client){
            throw $this->createNotFoundException('Aucun client trouvé');
        }
        $this->accessService->companyClientAccess($client);

        $intervention = new Intervention();
        $intervention->setStartAt(new \DateTimeImmutable('now'));
        $intervention->setEndAt(new \DateTimeImmutable('+ 1 hours'));
        $intervention->setClient($client);

        $formIntervention = $this->createForm(AddInterventionType::class, $intervention);

        $formIntervention->handleRequest($request);

        if($formIntervention->isSubmitted() && $formIntervention->isValid())
        {
            $intervention = $formIntervention->getData();

            $interventionResponse = $createIntervention->addIntervention($intervention);
            if($interventionResponse)
            {
                $this->addFlash('danger', $interventionResponse);
            }else
            {
                $this->addFlash('success', "L'intervention à bien été crée.");
                return $this->redirectToRoute("app_client_edit", ["id" => $client->getId()]);

            }
        }

        return $this->render('app/client/intervention/add.html.twig', [
            'form_intervention' => $formIntervention->createView(),
        ]);
    }

    /**
     * @Route("/view/{intervention}", name="app_intervention_view")
     * @param Intervention $intervention
     * @return Response
     */
    public function viewIntervention(Intervention $intervention = null,Request $request,
                                     InterventionInterface $createIntervention): Response
    {
        if(!$intervention){
            throw $this->createNotFoundException('Aucune Intervention trouvé');
        }
        $this->accessService->companyClientAccess($intervention->getClient());

        //form
        $formIntervention = $this->createForm(AddInterventionType::class, $intervention);

        $formIntervention->handleRequest($request);

        if($formIntervention->isSubmitted() && $formIntervention->isValid())
        {
            $intervention = $formIntervention->getData();

            $interventionResponse = $createIntervention->addIntervention($intervention);
            if($interventionResponse)
            {
                $this->addFlash('danger', $interventionResponse);
            }else
            {
                $this->addFlash('success', "L'intervention à bien été crée.");
                return $this->redirectToRoute("app_intervention_view", ["intervention" => $intervention->getId()]);

            }
        }

        return $this->render('app/client/intervention/view.html.twig', [
            'intervention' => $intervention,
            'form_intervention' => $formIntervention->createView(),
        ]);
    }
}