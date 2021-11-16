<?php

namespace App\Http\Controller\Intervention;

use App\Domain\Client\Entity\Client;
use App\Http\Form\AddFileType;
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
    public function addFile(Client $id = null,
                            Request $request) : Response
    {
        $client = $id;
        if(!$client){
            throw $this->createNotFoundException('Aucun client trouvé');
        }

        $this->accessService->companyClientAccess($client);

        return $this->render('app/client/intervention/add.html.twig');
    }
}