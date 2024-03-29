<?php

namespace App\Http\Admin\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\User\Entity\User;
use App\Http\Admin\Form\AddUserType;
use App\Infrastructure\User\UserService;


/**
 * Class UserAdminController
 * @package App\Http\Admin\Controller\User
 * @Route("/admin/user")
 */
class UserAdminController extends AbstractController
{
    /**
     * @Route("/add", name="admin_client_add")
     */
    public function addUser(Request $request, UserService $userService) :Response
    {
        $user = new User();

        $formUser = $this->createForm(AddUserType::class, $user);

        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $user = $formUser->getData();

            $responseUser = $userService->createAccount($user);

            if(!$responseUser){
                $this->addFlash('success', 'Le compte utilisateur à bien été crée.');
                return $this->redirectToRoute('app_index');
            }else{
                $this->addFlash('danger', $responseUser);
            }
        }
        return $this->render('admin/user/add.html.twig', [
            'form_user' => $formUser->createView(),
        ]);
    }
}