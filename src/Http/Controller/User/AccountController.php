<?php

namespace App\Http\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Http\Form\EditUserType;
use App\Domain\User\Entity\User;
use App\Infrastructure\User\UserService;


/**
 * Class AccountController
 * @package App\Http\Controller
 * @Route("/app/account/")
 */
class AccountController extends AbstractController
{

    /**
     * @Route("edit", name="app_account_edit")
     */
    public function editAccount(Request $request, UserService $userService) : Response
    {
        $formUser = $this->createForm(EditUserType::class, $this->getUser());

        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $user = $formUser->getData();


            $userResponse = $userService->editUserAccount($user);
            if ($userResponse) {
                $this->addFlash('danger', $userResponse);
            } else {
                $this->addFlash('success', "Les informations ont bien été enregistrées.");
            }
        }

        return $this->render('app/account/edit.html.twig', [
            'form_user' => $formUser->createView(),
        ]);
    }

    /**
     * @Route("company/edit", name="app_account_company_edit")
     */
    public function editCompanyAccount(Request $request, UserService $userService) : Response
    {
        $formUser = $this->createForm(EditUserType::class, $this->getUser());

        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $user = $formUser->getData();


            $userResponse = $userService->editUserAccount($user);
            if ($userResponse) {
                $this->addFlash('danger', $userResponse);
            } else {
                $this->addFlash('success', "Les informations ont bien été enregistrées.");
            }
        }

        return $this->render('app/account/edit.html.twig', [
            'form_user' => $formUser->createView(),
        ]);
    }
}