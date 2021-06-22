<?php

namespace App\Http\Admin\Controller\Core;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Domain\User\Entity\User;
/**
 * Class AdminController
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index")
     */
    public function index() : Response
    {

        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();


        return $this->render('admin/panel/index.html.twig', [
            'users' => $users
        ]);
    }

}