<?php

namespace App\Http\Controller\Contract;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Infrastructure\Security\AccessService;

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
    public function addContract(Client $client = null) : Response
    {
        if(!$client)
        {
            throw $this->createNotFoundException("Aucun client trouvé");
        }
        $this->accessService->companyClientAccess($client);

        $contract = new Contract();
        $contract->setClient($client);

        $formContract = $this->createForm(AddContractType::class, $contract);

        return $this->render('app/client/contract/add.html.twig', [
            'form_contract' => $formContract->createView(),
        ]);
    }
}