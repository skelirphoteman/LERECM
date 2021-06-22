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
        return $this->render('core/index.html.twig');
    }
}