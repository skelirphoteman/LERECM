<?php

namespace App\Http\Api\ApiAdmin;



use App\Domain\ApiAdminConnection\Entity\ApiAdminConnection;
use App\Infrastructure\ApiAdminPanel\SkelirPanelService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class APIPanelController
 * @package App\Http\Api\ApiAdmin
 * @Route("api/admin/panel")
 */
class APIPanelController extends AbstractController
{
    /**
     * @Route("/create/account", name="api_panel_create_company_account")
     */
    public function createAccount(Request $request,
                                    SkelirPanelService $skelirPanelService) : JsonResponse
    {

        $ApiAdminConnection = $this->getDoctrine()
            ->getRepository(ApiAdminConnection::class)
            ->findOneBy(['name' => $request->request->get('name')]);


        if(!$ApiAdminConnection || !password_verify($request->request->get('token'), $ApiAdminConnection->getPasswordToken())){


            return new JsonResponse(["Connexion refusé"]);
        }
        if(!$request->request->get('email') ||
            !$request->request->get('user_panel_id') ||
            !$request->request->get('subscription_panel_id') ||
            !$request->request->get('end_at')){
            return new JsonResponse('informations manquantes');
        }

        $create = $skelirPanelService->createAccount($request);

        return new JsonResponse("$create");
    }
}