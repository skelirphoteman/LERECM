<?php

namespace App\Http\Controller\Core;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Infrastructure\ChangePassword\ChangePasswordService;


class CoreController extends AbstractController
{
    /**
     * @Route("/", name="core")
     */
    public function core(): Response
    {
        return new Response('ok');
    }

}