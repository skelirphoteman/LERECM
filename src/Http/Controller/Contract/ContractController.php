<?php

namespace App\Http\Controller\Contract;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Infrastructure\Security\AccessService;
use App\Infrastructure\Contract\ContractService;

use App\Domain\Client\Entity\Client;
use App\Domain\Contract\Entity\Contract;
use App\Http\Form\AddContractType;
/**
 * Class ContractController
 * @package App\Http\Controller
 * @Route("app/contract/")
 */
class ContractController extends AbstractController
{
    private $accessService;

    public function __construct(AccessService $accessService)
    {
        $this->accessService = $accessService;
    }

    /**
     * @Route("add/{client}", name="app_client_contract_add")
     */
    public function addContract(Client $client = null, Request $request, ContractService $contractService) : Response
    {
        if(!$client)
        {
            throw $this->createNotFoundException("Aucun client trouvé");
        }
        $this->accessService->companyClientAccess($client);

        $contract = new Contract();
        $contract->setClient($client);

        $formContract = $this->createForm(AddContractType::class, $contract);

        $formContract->handleRequest($request);

        if($formContract->isSubmitted() && $formContract->isValid()){
            $contract = $formContract->getData();

            $contractResponse = $contractService->addContract($contract);

            if($contractResponse)
            {
                $this->addFlash('danger', $contractResponse);
            }else
            {
                $this->addFlash('success', "Contrat bien crée.");
                return $this->redirectToRoute("app_client_edit", ["id" => $client->getId()]);
            }
        }
        return $this->render('app/client/contract/add.html.twig', [
            'form_contract' => $formContract->createView(),
        ]);
    }

    /**
     * @Route("edit/{contract}", name="app_client_contract_edit")
     */
    public function editContract(Contract $contract = null, Request $request, ContractService $contractService) : Response
    {
        if(!$contract)
        {
            throw $this->createNotFoundException("Aucun contrat trouvé");
        }
        $this->accessService->companyClientAccess($contract->getClient());

        $formContract = $this->createForm(AddContractType::class, $contract);

        $formContract->handleRequest($request);

        if($formContract->isSubmitted() && $formContract->isValid()){
            $contract = $formContract->getData();

            $contractResponse = $contractService->addContract($contract);

            if($contractResponse)
            {
                $this->addFlash('danger', $contractResponse);
            }else
            {
                $this->addFlash('success', "Contrat bien modifié.");
                return $this->redirectToRoute("app_client_contract_edit", ["contract" => $contract->getId()]);
            }
        }
        return $this->render('app/client/contract/edit.html.twig', [
            'form_contract' => $formContract->createView(),
            "contract" => $contract,
        ]);
    }

    /************************** -- next payment -- **************************/
    /**
     * @Route("nextpayment/{contract}", name="app_client_contract_nextpayment")
     */
    public function nextPayment(Contract $contract = null, Request $request, ContractService $contractService)
    {
        if(!$contract)
        {
            throw $this->createNotFoundException("Aucun contrat trouvé");
        }
        $this->accessService->companyClientAccess($contract->getClient());

        $contractResponse = $contractService->nextPayment($contract);

        if($contractResponse)
        {
            $this->addFlash('danger', $contractResponse);
        }else
        {
            $this->addFlash('success', "Date de paiement bien mis à jour !");
        }

        if($request->get('redirection') == 0)
        {
            return $this->redirectToRoute("app_index");

        }
        return $this->redirectToRoute("app_client_contract_edit", ["contract" => $contract->getId()]);
    }

    /**
     * @Route("/delete/{contract}", name="app_client_contract_delete")
     */
    public function deleteContract(Contract $contract = null,ContractService $contractService) : Response
    {

        if(!$contract){
            throw $this->createNotFoundException('Aucun abonnement trouvé');
        }

        $this->accessService->companyClientAccess($contract->getClient());

        $delete = $contractService->deleteContract($contract);

        if($delete)
        {
            $this->addFlash('danger', $delete);
        }else
        {
            $this->addFlash('success', "L'abonnement a bien été supprimer.");
        }

        return $this->redirectToRoute('app_client_edit', ["id" => $contract->getClient()->getId()]);
    }

}