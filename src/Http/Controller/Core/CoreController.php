<?php

namespace App\Http\Controller\Core;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Infrastructure\ChangePassword\ChangePasswordService;
use App\Domain\ApiAdminConnection\Entity\ApiAdminConnection;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class CoreController extends AbstractController
{
    /**
     * @Route("/", name="core")
     */
    public function core(): Response
    {
        return new Response('ok');
    }

    /**
     * @Route("/create", name="test")
     */
    public function create(UserPasswordEncoderInterface $encoder) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();


        $apiconnection = new ApiAdminConnection();
        $apiconnection->setName("skelir_panel");
        $token = "dz4%?rXqPjp/E<J!Nm-,3^;B`VMgWh{n52}(SDt7wKx_[C)F*HpJ!%a@~j-6zgG<m8usAdFbKe#`27*n.}SQhUBy3^?c4E[/D:9Y";
        $apiconnection->setPasswordToken(password_hash($token, PASSWORD_BCRYPT));

        $entityManager->persist($apiconnection);

        $entityManager->flush();
        return new Response('ok');
    }

}