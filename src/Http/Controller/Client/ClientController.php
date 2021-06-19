<?php
namespace App\Http\Controller\Client;

use App\Domain\Documents\Entity\Invoice;
use App\Domain\Documents\Entity\Quote;
use App\Domain\Client\Entity\Client;
use App\Domain\UserClient\Entity\UserClient;
use App\Http\Form\AddClientType;
use App\Infrastructure\Client\ClientService;
use App\Infrastructure\User\UserClientService;
use App\Infrastructure\Security\AccessService;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class InterfaceController
 * @package App\Http\Controller\Panel
 * @Route("/app/client")
 */

class ClientController extends AbstractController
{
    /**
     * @Route("/add", name="app_client_add")
     */
    public function addClient(Request $request, ClientService $clientService) : Response
    {

        $client = new Client($this->getUser());

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

    /**
     * @Route("/edit/{id}", name="app_client_edit")
     */
    public function editClient(Client $id = null,
                               Request $request,
                               ClientService $clientService,
                                AccessService $accessService) : Response
    {
        $client = $id;
        if(!$client){
            throw $this->createNotFoundException('Aucun client trouvé');
        }

        $accessService->companyClientAccess($client);

        $userClient = $this->getDoctrine()
            ->getRepository(UserClient::class)
            ->findOneByUuid($id->getId());

        $userClientIsCreated = false;

        if($userClient)
        {
            $userClientIsCreated = true;
        }

        $formClient = $this->createForm(AddClientType::class, $client);

        $formClient->handleRequest($request);

        if ($formClient->isSubmitted() && $formClient->isValid()) {
            $client = $formClient->getData();

            $responseClient = $clientService->addClient($client, $this->getUser());

            if(!$responseClient){
                $this->addFlash('success', 'Le client a bien été modifié.');
            }else{
                $this->addFlash('danger', $responseClient);
            }
        }

        $invoices = $this->getDoctrine()
            ->getRepository(Invoice::class)
            ->findBy(["client" => $client], ["created_at" => "DESC"]);
        $quotes = $this->getDoctrine()
            ->getRepository(Quote::class)
            ->findBy(["client" => $client], ["created_at" => "DESC"]);

        return $this->render('app/client/edit.html.twig', [
            'form_client' => $formClient->createView(),
            'client' => $client,
            'userClient' => $userClientIsCreated,
            'invoices' => $invoices,
            'quotes' => $quotes,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_client_delete")
     */
    public function deleteClient(Client $id = null, Request $request, ClientService $clientService) : Response
    {
        $client = $id;
        if(!$client){
            throw $this->createNotFoundException('Aucun client trouvé');
        }

        if(!$client->getCompany()->getUsers()->contains($this->getUser()))
        {
            throw $this->createAccessDeniedException('Vous n\'avez pas accéss à cette page.');
        }

        $responseClient = $clientService->deleteClient($client, $this->getUser());

        if(!$responseClient){
            $this->addFlash('success', 'Le client a bien été supprimé.');
            return $this->redirectToRoute('app_client_list');
        }else{
            $this->addFlash('danger', $responseClient);
            return $this->redirectToRoute('app_client_edit', ['id' => $client->getId()]);
        }

    }

    /**
     * @Route("/generate/account/{id}", name="app_client_generate_account")
     */
    public function generateClientAccount(Client $id = null, UserClientService $userClientService)
    {
        $client = $id;
        if(!$client){
            throw $this->createNotFoundException('Aucun client trouvé');
        }

        if(!$client->getCompany()->getUsers()->contains($this->getUser()))
        {
            throw $this->createAccessDeniedException('Vous n\'avez pas accéss à cette page.');
        }

        $userClient = $this->getDoctrine()
            ->getRepository(UserClient::class)
            ->findOneByUuid($id);

        if($userClient)
        {
            $userClientResponse = $userClientService->updateClientAccount($this->getUser(), $client, $userClient);

            if($userClientResponse){
                $this->addFlash('danger', $userClientResponse);
                return $this->redirectToRoute('app_client_edit', ["id" => $id->getId()]);
            }
        }else 
        {
            $userClientResponse = $userClientService->createClientAccount($this->getUser(), $client);

            if($userClientResponse){
                $this->addFlash('danger', $userClientResponse);
                return $this->redirectToRoute('app_client_edit', ["id" => $id->getId()]);
            }
        }

       
        return $this->redirectToRoute('app_client_edit', ["id" => $id->getId()]);
    }


    /**
     * @Route("/list", name="app_client_list")
     */
    public function listClient(Request $request) : Response
    {

        return $this->render('app/client/list.html.twig');

    }

    /**
     * @Route("/list/api", name="app_client_list_api")
     */
    public function listClientAPI(SerializerInterface $serializer, Request $request) : JsonResponse
    {
        $repository = $this->getDoctrine()
            ->getRepository(Client::class);

        $users = $repository
            ->createQueryBuilder('u')
            ->select('u.id', 'u.email', 'u.name', 'u.surname', 'u.avenue', 'u.postal_code', 'u.company_name', 'u.city')
            ->where('u.name LIKE :search')
            ->orWhere('u.surname LIKE :search')
            ->orWhere('u.avenue LIKE :search')
            ->orWhere('u.postal_code LIKE :search')
            ->orWhere('u.company_name LIKE :search')
            ->orWhere('u.city LIKE :search')
            ->setParameter("search", "%" . $request->request->get('search'). "%")
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();

        $json = $serializer->serialize($users, 'json');
        return new JsonResponse( $json);
    }
    
}