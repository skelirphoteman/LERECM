<?php
namespace App\Http\Controller\Client;

use App\Domain\Client\Entity\Client;
use App\Http\Form\AddClientType;
use App\Infrastructure\Client\ClientService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class InterfaceController
 * @package App\Http\Controller\Panel
 * @Route("/app/client")
 */

class ClientController extends AbstractController
{
    /**
     * @Route("/add/{id}", name="app_client_add")
     */
    public function addClient(Client $id = null, Request $request, ClientService $clientService) : Response
    {
        $client = $id;
        if(!$client){
            $client = new Client($this->getUser());
        }

        $formClient = $this->createForm(AddClientType::class, $client);

        $formClient->handleRequest($request);

        if ($formClient->isSubmitted() && $formClient->isValid()) {
            $client = $formClient->getData();

            $responseClient = $clientService->addClient($client, $this->getUser());

            if(!$responseClient){
                $this->addFlash('success', 'Le client a bien été ajouté.');
                return $this->redirectToRoute('app_index');
            }else{
                $this->addFlash('danger', $responseClient);
            }
        }

        return $this->render('app/client/add.html.twig', [
            'form_client' => $formClient->createView()
        ]);
    }
}